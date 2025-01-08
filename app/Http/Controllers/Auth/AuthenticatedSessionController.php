<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'role' => ['required', 'string'],
            'password' => ['required'],
        ]);

        try {
            $user = User::role($request->role)->first();
            
            if (!$user) {
                return back()
                    ->withInput($request->only('role'))
                    ->withErrors(['role' => 'Invalid credentials.']);
            }

            Auth::login($user, $request->boolean('remember'));

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->hasRole('oca')) {
                return redirect()->intended(route('oca.dashboard'));
            } elseif ($user->hasRole(['bucc', 'buac', 'robu', 'bizbee'])) {
                return redirect()->intended(route('club.dashboard'));
            }

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('role'))
                ->withErrors(['role' => 'Authentication failed.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
