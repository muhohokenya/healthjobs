<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role = null, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (!$authGuard->user()->hasAnyRole($roles)) {
            // For Inertia.js applications, return proper response
            if ($request->expectsJson() || $request->header('X-Inertia')) {
                return response()->json([
                    'message' => 'You do not have the required roles to access this resource.'
                ], 403);
            }

            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
