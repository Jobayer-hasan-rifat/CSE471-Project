<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * The allowed roles in the system.
     */
    protected $allowedRoles = [
        'oca',
        'admin',
        'bucc',
        'robu',
        'bizbee',
        'buedf'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Get user's role
        $userRole = $request->user()->role;

        // Check if user's role is allowed in the system
        if (!in_array($userRole, $this->allowedRoles)) {
            abort(403, 'Unauthorized role.');
        }

        // Check if user has required role for this route
        $allowedRoles = is_array($roles) ? $roles : explode('|', $roles[0]);
        if (!empty($roles) && !in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
