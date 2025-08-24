<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PharmacyBoardVerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{

    public function __construct(
        private readonly PharmacyBoardVerificationService $verificationService
    ) {

    }
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $validator = \Validator::make($request->all(),[
            'licence_number' => [
                'nullable',
                'size:11',
                'unique:facilities,licence_number'
            ],
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validator->after(function ($validator) use ($request) {
            $practitionerLicence = $request->only(['licence_number']);

            $licence_number = $request->only('licence_number')['licence_number'];

            if ($licence_number!==null) {
                $verification = $this->verificationService->verifyPractitioner($practitionerLicence['licence_number'],
                $request,false
                );


                if ($verification['success'] === false) {
                    $validator->errors()->add(
                        'licence_number',
                        'No matching facility found in the registry with the Licence Number '.$request->licence_number
                    );
                }
            }



        });
        $validated = $validator->validate();

        $licence_number = $validated['licence_number'];

        $payload = [
            'licence_number'=>'',
            'name' => '',
            'expiry_date' => '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($licence_number!==null) {
            $verification = $this->verificationService->verifyPractitioner($licence_number,$request,false);

            $payload['licence_number'] = $verification['data']['licence_number'];
            $payload['name'] = $verification['data']['name'];
            $payload['expiry_date'] = $verification['data']['expiry_date'];


            if ($verification['success'] === false) {
                dd("I am just an intern I don't have a licence number ");
                $name = $validated['name'];
                $licence_number = '';// We assume you don't have a licence yet
            }else{
                $name = $validated['name'];
                $licence_number = $verification['data']['licence_number'];
            }
        }else{

            $payload['licence_number'] = '';
            $payload['name'] = $validated['name'];
            $payload['expiry_date'] = '';

        }

        $user = User::create($payload);

        $user->givePermissionTo('access-dashboard');

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
