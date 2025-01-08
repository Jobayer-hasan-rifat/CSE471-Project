<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VenueController extends Controller
{
    /**
     * Display a listing of venues.
     */
    public function index()
    {
        $venues = Venue::withCount(['events' => function ($query) {
            $query->where('status', 'approved');
        }])->paginate(10);
        
        return view('venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new venue.
     */
    public function create()
    {
        return view('venues.create');
    }

    /**
     * Store a newly created venue in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:venues',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'is_available' => 'required|boolean'
        ]);

        $venue = Venue::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'location' => $request->location,
            'description' => $request->description,
            'is_available' => $request->is_available
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue created successfully.');
    }

    /**
     * Display the specified venue.
     */
    public function show(Venue $venue)
    {
        $upcomingEvents = $venue->events()
            ->where('status', 'approved')
            ->whereDate('start_date', '>=', Carbon::now())
            ->orderBy('start_date')
            ->paginate(10);
            
        return view('venues.show', compact('venue', 'upcomingEvents'));
    }

    /**
     * Show the form for editing the specified venue.
     */
    public function edit(Venue $venue)
    {
        return view('venues.edit', compact('venue'));
    }

    /**
     * Update the specified venue in storage.
     */
    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:venues,name,' . $venue->id,
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'is_available' => 'required|boolean'
        ]);

        $venue->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'location' => $request->location,
            'description' => $request->description,
            'is_available' => $request->is_available
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    /**
     * Remove the specified venue from storage.
     */
    public function destroy(Venue $venue)
    {
        // Check if venue has any upcoming approved events
        $hasUpcomingEvents = $venue->events()
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->exists();
            
        if ($hasUpcomingEvents) {
            return back()->with('error', 'Cannot delete venue with upcoming events.');
        }
        
        $venue->delete();
        
        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully.');
    }

    /**
     * Generate venue usage reports
     */
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonth());
        $endDate = $request->get('end_date', Carbon::now());
        
        $venues = Venue::withCount(['events' => function ($query) use ($startDate, $endDate) {
            $query->where('status', 'approved')
                ->whereBetween('start_date', [$startDate, $endDate]);
        }])->get();
        
        $monthlyUsage = Event::where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->selectRaw('MONTH(start_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $venueCapacityUtilization = Event::where('status', 'approved')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->with('venue')
            ->get()
            ->groupBy('venue.name')
            ->map(function ($events) {
                $venue = $events->first()->venue;
                $avgAttendance = $events->avg('expected_attendance');
                return [
                    'capacity' => $venue->capacity,
                    'avg_attendance' => round($avgAttendance, 2),
                    'utilization' => round(($avgAttendance / $venue->capacity) * 100, 2)
                ];
            });
        
        return view('reports.venues', compact('venues', 'monthlyUsage', 'venueCapacityUtilization'));
    }
}
