<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;

class EventPlannerController extends Controller
{
    public function index()
    {
        // Fetch user and club information
        $userEmail = auth()->user()->email;
        $clubInfo = Http::get("https://clubsyncserver.vercel.app/dashboard-info/{$userEmail}")->json();
        $pendingRequests = Http::get("https://clubsyncserver.vercel.app/pending-requests/{$userEmail}")->json();
        $respondedRequests = Http::get("https://clubsyncserver.vercel.app/responded-requests/{$userEmail}")->json();

        return view('event-planner', compact('clubInfo', 'pendingRequests', 'respondedRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date|after_or_equal:today',
            'termsAgreed' => 'required|boolean',
            // Add other validation rules as needed
        ]);

        // Check room availability if needed
        if ($request->needsRoom) {
            $roomResponse = Http::post('https://clubsyncserver.vercel.app/check-room-availability', [
                'date' => $request->date,
                'roomNumber' => $request->roomNumber,
            ]);

            if (!$roomResponse->json()['available']) {
                return back()->withErrors(['room' => 'This room is already booked for the selected date.']);
            }
        }

        // Store the event proposal
        Http::post('https://clubsyncserver.vercel.app/new-event', [
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'budget' => $request->budget,
            'status' => 'Pending',
            'clubMail' => $userEmail,
            'advisorEmail' => $clubInfo['advisors'][0]['advisorEmail'],
            // Add other fields as needed
        ]);

        return redirect()->back()->with('success', 'Request Sent');
    }
}
