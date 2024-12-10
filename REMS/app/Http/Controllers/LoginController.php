<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            "email" => "required|email",
            "password" => "required"
        ]);
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return back()->with("error", "Invalid email or password");
        }
        if (!Hash::check($request->password, $user->password)) {
            return back()->with("error", "Invalid email or password");
        }
        Auth::login($user);
        return redirect()->route('home')->with("success", "Logged in successfully");
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with("success", "Logged out successfully");
    }
}
