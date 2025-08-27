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
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $licence_number = $request->get('licence');


        if($licence_number!=null) {

            $speciality = $request->get('speciality');
            if ($speciality == 'nurse') {
                $verification = $this->verificationService->verifyNurse($licence_number);

                dd($verification);
            }
            // Verify the licence with Pharmacy Poisons Board
            $verification = $this->verificationService->verifyPractitioner($licence_number,$request,false);

            if($verification['success']){
                $user = $request->user();
                $user->licence_number = $verification['data']['licence_number'];
                $user->licence_number_expiry = $verification['data']['expiry_date'];
                $user->name = $verification['data']['name']; //FULL NAME AS REGISTERED BY PBB
                $user->licence_status = $verification['data']['status'];
            }else{
                $request->session()->forget('flashMessage');
                return redirect(route('profile.edit'),303)->with(
                    'flashMessage', $verification['message']
                );
            }
        }

        $request->user()->save();

        return to_route('profile.edit');
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
