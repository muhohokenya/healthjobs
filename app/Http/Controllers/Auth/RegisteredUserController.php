<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $request->validate([
            'role' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);



        $selected_role = $request->get('role');

        $payload = [
            'selected_role' => $selected_role,
            'licence_number' => '',
            'name' => '',
            'expiry_date' => '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($payload);

        $user->assignRole($selected_role);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
