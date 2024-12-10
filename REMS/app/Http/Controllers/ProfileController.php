<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            // Add other fields as necessary
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'email'));
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
