<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'User is not logged in.');
        }

        $user = Auth::user();
        
        // Debug information
        Log::info('User roles check', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'requested_roles' => $roles,
            'user_roles' => $user->getRoleNames(),
        ]);

        // Split role strings that contain multiple roles
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, explode('|', $role));
        }

        // Check if user has any of the allowed roles
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        Log::warning('User does not have required role', [
            'user_id' => $user->id,
            'required_roles' => $allowedRoles,
            'user_roles' => $user->getRoleNames(),
        ]);

        return redirect()->route('welcome')
            ->with('error', 'You do not have permission to access this page.');
    }
}
