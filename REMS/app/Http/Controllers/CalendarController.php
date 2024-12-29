<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function getEvents()
    {
        $events = Event::where('status', 'approved')
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
        $event = Event::findOrFail($id);
        return view('calendar.index', compact('event'));
    }
}
