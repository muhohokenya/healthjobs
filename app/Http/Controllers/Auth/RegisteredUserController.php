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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            // Explicitly hash to be clear, even though the model casts password as hashed
            'password' => Hash::make($validated['password']),
        ];

        if (! empty($validated['role'])) {
            $payload['selected_role'] = $validated['role'];
        }

        $user = User::create($payload);

        if (! empty($validated['role'])) {
            $user->assignRole($validated['role']);
        }

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
