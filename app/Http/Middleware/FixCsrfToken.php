<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

class FixCsrfToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('_token')) {
            return $next($request);
        }
        
        $request->session()->regenerateToken();
        return redirect()->back()->withErrors(['message' => 'Your session has expired. Please try again.']);
    }
}
