<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        // Process the booking (e.g., save to the database)
        // Booking::create($validatedData); // Uncomment this line if you have a Booking model

        // Redirect or return a response
        return redirect()->back()->with('success', 'Booking submitted successfully!');
    }
}