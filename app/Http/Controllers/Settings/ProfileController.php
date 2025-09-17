<?php

namespace App\Http\Controllers\Settings;

use App\Events\FacilityVerifiedEvent;
use App\ExternalLibraries\Cropper\Slim;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Models\User;
use App\Services\LicenseVerificationService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\NoReturn;

class ProfileController extends Controller
{
    public function __construct(
        private readonly LicenseVerificationService $verificationService
    ) {}

    public function index(): Response
    {
        $profiles = User::with(['roles.permissions']) // Eager load roles and permissions
        ->leftJoin('sessions', function ($join) {
            $join->on('users.id', '=', 'sessions.user_id')
                ->whereRaw('sessions.last_activity = (
                    SELECT MAX(last_activity)
                    FROM sessions s2
                    WHERE s2.user_id = users.id
                )');
        })
            ->where('users.selected_role', '=', 'job-seeker')
            ->select([
                'users.*',
                'sessions.last_activity'
            ])
            ->orderBy('sessions.last_activity', 'desc') // Optional: Order by session activity
            ->orderBy('users.id') // Order by user id if needed
            ->get()
            ->map(function ($user) {
                // Calculate last_seen in seconds
                $user->last_seen = $user->last_activity
                    ? \Carbon\Carbon::createFromTimestamp($user->last_activity)->diffInSeconds(now())
                    : 'Never';

                // Determine if user is online based on last activity (within 5 days)
                $user->is_online = $user->last_activity && \Carbon\Carbon::createFromTimestamp($user->last_activity)->diffInDays(now()) < 5;

                // Remove raw last_activity field to avoid duplication
                unset($user->last_activity);

                return $user;
            });

        return Inertia::render('medics/profiles', [
            'profiles' => $profiles,
        ]);
    }


    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $isProfileComplete = $request->user()->isProfileComplete() ?? false;

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'isProfileComplete' => $isProfileComplete,
        ]);
    }

    #[NoReturn]
    public function updateAvatar(Request $request): Response
    {
        dd($request->all());

    }

    /**
     * Update the user's profile information.
     */
    #[NoReturn]
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {


        $user = $request->user();
        $avatarPath = null;

        // Handle optional Slim image payload safely
        $slimPayload = $request->input('slim');
        if (is_array($slimPayload) && ! empty($slimPayload[0])) {
            $decodedData = json_decode($slimPayload[0], true);

            if (is_array($decodedData) && isset($decodedData['output']['image'])) {
                // Get the base64 image data
                $imageData = $decodedData['output']['image'];
                $parts = explode(',', (string) $imageData, 2);
                if (count($parts) === 2) {
                    $base64Data = $parts[1];
                    $binaryData = base64_decode($base64Data, true);

                    if ($binaryData !== false) {
                        // Delete old avatar if it exists
                        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                            Storage::disk('public')->delete($user->avatar);
                        }

                        // Generate filename with user ID for better organization
                        $filename = 'avatars/user_' . $user->id . '_' . uniqid('', true) . '.jpg';
//                        Storage::disk('public')->put($filename, $binaryData);

                        Storage::disk('public')->put($filename, $binaryData, ['visibility' => 'public']);


                        // Store the file path for the avatar
                        $avatarPath = $filename;

                        // Store additional data if needed elsewhere
                        $decodedData['output']['data'] = $filename;
                        $decodedData['output']['url'] = Storage::url($filename);
                    }
                }

                // Clean up unused fields if present
                unset($decodedData['server'], $decodedData['meta']);
                if (isset($decodedData['output']['image'])) {
                    unset($decodedData['output']['image']);
                }
                if (isset($decodedData['input']['field'])) {
                    unset($decodedData['input']['field']);
                }
                if (isset($decodedData['input'])) {
                    $decodedData['input']['data'] = null;
                }
            }
        }

        // Fill user data with validated request data
        $user->fill($request->validated());

        // Set the avatar path if an image was uploaded
        if ($avatarPath) {
            $user->avatar = $avatarPath;
        }

        if ($user->isDirty(['email', 'contacts'])) {
            $user->email_verified_at = null;
        }

        $licenceNumber = $request->get('licence');

        if ($licenceNumber) {
            $verificationResult = $this->handleLicenceVerification($request, $licenceNumber);
            if (! $verificationResult['success']) {
                return $this->redirectWithError($verificationResult['message']);
            }

            $this->updateUserLicenceData($user, $verificationResult['data']);
        }

        $user->save();

        return redirect()->route('profile.edit');
    }

    /**
     * Handle licence verification based on speciality
     */
    private function handleLicenceVerification(ProfileUpdateRequest $request, string $licenceNumber): array
    {
        $role = $request->user()->roles[0]['name'];

        if ($role === 'recruiter') {
            $facilityData = [
                'license_number' => $licenceNumber,
                'name' => $request->name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
            ];

            $verified = $this->verificationService->verifyFacility($facilityData);
            //            dd($verified);

            event(new FacilityVerifiedEvent($verified));

            return $verified;
        }

        $speciality = $request->get('profession');

        if ($speciality === 'nurse') {
            return $this->verificationService->verifyNurse($licenceNumber, $request);
        } elseif ($speciality === 'pharmacist') {
            return $this->verificationService->verifyPharmacy($licenceNumber, $request, false, 2);
        } elseif ($speciality === 'pharm_tech') {
            return $this->verificationService->verifyPharmacy($licenceNumber, $request, false, 4);
        } elseif ($speciality === 'lab_technician') {
            return $this->verificationService->searchKMLTTB($licenceNumber, $request);
        } elseif ($speciality === 'clinician') {
            return $this->verificationService->verifyClinician($licenceNumber, $request);
        }

        return [
            'success' => false,
            'message' => 'There was an error verifying your licence number.',
        ];

    }

    /**
     * Update user with verified licence data
     */
    private function updateUserLicenceData($user, array $licenceData): void
    {
        //        dd($licenceData);
        $user->licence_number = $licenceData['license_number'];
        $user->licence_number_expiry = $licenceData['licence_expiry_date'];
        $user->name = $licenceData['name']; // FULL NAME AS REGISTERED BY PBB
        $user->licence_status = $licenceData['license_number_validity'];
    }

    /**
     * Redirect with error message
     */
    private function redirectWithError(string $message): RedirectResponse
    {
        session()->forget('flashMessage');

        return redirect(route('profile.edit'), 303)->with('flashMessage', $message);
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
