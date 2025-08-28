<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Services\LicenseVerificationService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(
        private readonly LicenseVerificationService $verificationService
    ) {

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
            'isProfileComplete' => $isProfileComplete
        ]);
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $licenceNumber = $request->get('licence');

        if ($licenceNumber) {
            $verificationResult = $this->handleLicenceVerification($request, $licenceNumber);
            if (!$verificationResult['success']) {
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
        $speciality = $request->get('speciality');

        if ($speciality === 'nurse') {
            return $this->verificationService->verifyNurse($licenceNumber,$request);
        } else if ($speciality === 'pharmacist') {
            return $this->verificationService->verifyPharmacy($licenceNumber, $request, false,2);
        }else if ($speciality === 'pharm_tech') {
            return $this->verificationService->verifyPharmacy($licenceNumber, $request, false,4);
        }else if ($speciality === 'lab_technician') {
            return $this->verificationService->searchKMLTTB($licenceNumber,$request);
        }else if($speciality === 'clinician'){
            return $this->verificationService->verifyClinician($licenceNumber,$request);
        }

        return [
            'success' => false,
            'message' => "There was an error verifying your licence number.",
        ];

    }

    /**
     * Update user with verified licence data
     */
    private function updateUserLicenceData($user, array $licenceData): void
    {
        $user->licence_number = $licenceData['licence_number'];
        $user->licence_number_expiry = $licenceData['expiry_date'];
        $user->name = $licenceData['name']; // FULL NAME AS REGISTERED BY PBB
        $user->licence_status = $licenceData['status'];
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
