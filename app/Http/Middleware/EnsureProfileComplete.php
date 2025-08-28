<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if profile is complete
        $isProfileComplete = $request->user()->isProfileComplete() ?? false;

        if (!$isProfileComplete) {
            // Redirect to profile completion page or return error
            return redirect()->route('profile.complete')
                ->with('error', 'Please complete your profile to continue.');
        }

        return $next($request);
    }
}
