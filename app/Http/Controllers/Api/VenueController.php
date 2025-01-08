<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VenueController extends Controller
{
    /**
     * Check venue availability for given time period
     */
    public function checkAvailability(Request $request, Venue $venue): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $available = !Event::hasConflicts(
            $venue->id,
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'available' => $available,
            'venue' => [
                'id' => $venue->id,
                'name' => $venue->name,
                'capacity' => $venue->capacity
            ]
        ]);
    }
}
