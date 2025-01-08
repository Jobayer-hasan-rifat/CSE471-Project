<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();
        $calendar = [];
        $today = Carbon::now()->format('Y-m-d');
        
        // Get the start and end of the month
        $start = $date->copy()->startOfMonth()->startOfWeek();
        $end = $date->copy()->endOfMonth()->endOfWeek();
        
        // Get all events for this month
        $events = Event::where('status', 'approved')
            ->whereBetween('start_date', [$start, $end])
            ->with(['club', 'venue'])
            ->get();
            
        // Build the calendar array
        for ($day = $start; $day->lte($end); $day->addDay()) {
            $dayKey = $day->format('Y-m-d');
            $calendar[$dayKey] = $events->filter(function($event) use ($day) {
                return Carbon::parse($event->start_date)->format('Y-m-d') === $day->format('Y-m-d');
            });
        }
        
        // Get upcoming events
        $upcomingEvents = Event::where('status', 'approved')
            ->where('start_date', '>=', Carbon::now())
            ->with(['club', 'venue'])
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        return view('oca.calendar', [
            'calendar' => $calendar,
            'currentMonth' => $date->format('F Y'),
            'prevMonth' => $date->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $date->copy()->addMonth()->format('Y-m'),
            'today' => $today,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function getEvents()
    {
        $events = Event::where('status', 'approved')
            ->with(['club', 'venue'])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'club' => $event->club->name,
                    'venue' => $event->venue->name,
                ];
            });

        return response()->json($events);
    }

    public function showEvent($id)
    {
        $event = Event::with(['club', 'venue'])->findOrFail($id);
        return view('oca.calendar', compact('event', 'events'));
    }

    /**
     * Display the OCA calendar view
     */
    public function ocaIndex()
    {
        $events = Event::with(['club', 'venue'])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->event_name,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'club' => $event->club->name,
                    'venue' => $event->venue->name,
                    'description' => $event->description,
                    'status' => $event->status,
                    'review_url' => $event->status === 'pending' ? route('oca.events.approval', $event) : null,
                ];
            });

        return view('oca.events.calendar', compact('events'));
    }

    /**
     * Get events for calendar (AJAX)
     */
    public function ocaEvents()
    {
        $events = Event::with(['club', 'venue'])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->event_name,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'club' => $event->club->name,
                    'venue' => $event->venue->name,
                    'description' => $event->description,
                    'status' => $event->status,
                    'review_url' => $event->status === 'pending' ? route('oca.events.approval', $event) : null,
                ];
            });

        return response()->json($events);
    }
}
