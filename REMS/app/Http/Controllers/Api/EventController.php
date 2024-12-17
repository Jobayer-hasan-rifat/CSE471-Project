<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * Get events for calendar
     */
    public function calendar(Request $request): JsonResponse
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'venue_id' => 'nullable|exists:venues,id',
            'club_id' => 'nullable|exists:clubs,id'
        ]);

        $query = Event::query()
            ->where('status', 'approved')
            ->whereBetween('start_date', [$request->start, $request->end]);

        if ($request->venue_id) {
            $query->where('venue_id', $request->venue_id);
        }

        if ($request->club_id) {
            $query->where('club_id', $request->club_id);
        }

        $events = $query->with(['club', 'venue'])->get();

        return response()->json($events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->start_date->format('Y-m-d\TH:i:s'),
                'end' => $event->end_date->format('Y-m-d\TH:i:s'),
                'club' => $event->club->name,
                'venue' => $event->venue->name,
                'url' => route('events.show', $event)
            ];
        }));
    }
}
