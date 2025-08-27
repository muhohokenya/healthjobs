<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LicenseVerificationService;
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
            'role' => ['required', 'string'],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $selected_role = $request->get('role');

        $payload = [
            'selected_role'=>$selected_role,
            'licence_number'=>'',
            'name' => '',
            'expiry_date' => '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];


        $user = User::create($payload);

        $user->assignRole($selected_role);

        event(new Registered($user));

        Auth::login($user);

        return to_route('profile.update');
    }
}
