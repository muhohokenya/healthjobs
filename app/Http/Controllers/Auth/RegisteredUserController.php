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
use Spatie\Permission\Models\Role;

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
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $payload = [
            'selected_role'=>$request->get('role'),
            'licence_number'=>'',
            'name' => '',
            'expiry_date' => '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];


        $user = User::create($payload);

        $role = $request->get('role');

        $user->assignRole($role);

        event(new Registered($user));

        Auth::login($user);

        return to_route('profile.update');
    }
}
