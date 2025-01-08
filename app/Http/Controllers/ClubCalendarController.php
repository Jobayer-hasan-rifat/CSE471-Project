<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Club;
use Illuminate\Support\Facades\Auth;

class ClubCalendarController extends Controller
{
    private $colors = [
        '#3b82f6', // blue
        '#ec4899', // pink
        '#8b5cf6', // purple
        '#14b8a6', // teal
        '#f97316', // orange
        '#84cc16', // lime
        '#06b6d4', // cyan
        '#a855f7', // purple
        '#f43f5e', // rose
        '#10b981', // emerald
    ];

    public function index()
    {
        $clubs = Club::all();
        $colors = $this->colors;
        return view('club.calendar.index', compact('clubs', 'colors'));
    }

    public function events()
    {
        $user = Auth::user();
        $userClub = Club::where('user_id', $user->id)->first();
        
        if (!$userClub) {
            abort(403, 'You must be a club member to view this calendar.');
        }

        // Get all events from all clubs
        $events = Event::with(['club', 'venue'])
                      ->orderBy('start_datetime')
                      ->get()
                      ->map(function ($event) use ($userClub) {
                          $isMyClub = $event->club_id === $userClub->id;
                          
                          // Generate a unique color for each club
                          $clubColor = $this->getClubColor($event->club_id);
                          
                          return [
                              'id' => $event->id,
                              'title' => $event->title,
                              'start' => $event->start_datetime,
                              'end' => $event->end_datetime,
                              'description' => $event->description,
                              'venue' => optional($event->venue)->name,
                              'status' => $event->status,
                              'color' => $isMyClub ? $this->getEventColor($event->status) : $clubColor,
                              'textColor' => '#ffffff',
                              'club' => $event->club->name,
                              'isMyClub' => $isMyClub,
                              'extendedProps' => [
                                  'description' => $event->description,
                                  'venue' => optional($event->venue)->name,
                                  'club' => $event->club->name,
                                  'status' => $event->status
                              ]
                          ];
                      });

        return response()->json($events);
    }

    private function getEventColor($status)
    {
        return match ($status) {
            'pending' => '#fbbf24', // Yellow for pending
            'approved' => '#22c55e', // Green for approved
            'rejected' => '#ef4444', // Red for rejected
            default => '#6366f1'     // Indigo for other statuses
        };
    }

    private function getClubColor($clubId)
    {
        // Use modulo to cycle through colors if there are more clubs than colors
        return $this->colors[$clubId % count($this->colors)];
    }
}
