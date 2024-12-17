<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Club;
use App\Models\Venue;
use App\Models\EventDocument;
use App\Notifications\EventApproved;
use App\Notifications\EventRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('oca')) {
            $events = Event::with(['club', 'venue'])->latest()->paginate(10);
        } else {
            $club = Club::where('email', $user->email)->first();
            $events = Event::where('club_id', $club->id)
                ->with(['venue'])
                ->latest()
                ->paginate(10);
        }
        
        return view('events.index', compact('events'));
    }

    /**
     * Display event reports and statistics
     */
    public function reports(Request $request)
    {
        $startDate = Carbon::parse($request->get('start_date', now()->subMonth()));
        $endDate = Carbon::parse($request->get('end_date', now()));

        // Get basic statistics
        $stats = Event::getStats();

        // Get monthly statistics
        $monthlyStats = Event::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get venue usage statistics
        $venueStats = Event::whereBetween('created_at', [$startDate, $endDate])
            ->select('venues.name', DB::raw('COUNT(*) as count'))
            ->join('venues', 'events.venue_id', '=', 'venues.id')
            ->groupBy('venues.id', 'venues.name')
            ->orderByDesc('count')
            ->get();

        // Get recent events
        $recentEvents = Event::with(['club', 'venue'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('reports.events', compact(
            'stats',
            'monthlyStats',
            'venueStats',
            'recentEvents'
        ));
    }

    /**
     * Show calendar view of events
     */
    public function calendar()
    {
        $venues = Venue::all();
        $clubs = Club::all();

        return view('events.calendar', compact('venues', 'clubs'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $venues = Venue::all();
        return view('events.create', compact('venues'));
    }

    /**
     * Store a new event
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'venue_id' => 'required|exists:venues,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'expected_attendance' => 'required|integer|min:1',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
        ]);

        // Check venue capacity
        $venue = Venue::findOrFail($request->venue_id);
        if ($request->expected_attendance > $venue->capacity) {
            return back()->withErrors(['expected_attendance' => 'Expected attendance exceeds venue capacity.']);
        }

        // Check for scheduling conflicts
        if (Event::hasConflicts($request->venue_id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['venue_id' => 'The venue is not available for the selected time period.']);
        }

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'venue_id' => $request->venue_id,
            'club_id' => auth()->user()->club->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'expected_attendance' => $request->expected_attendance,
            'status' => 'pending'
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('event-documents');
                EventDocument::create([
                    'event_id' => $event->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event booking request submitted successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load(['club', 'venue', 'documents']);
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $venues = Venue::all();
        return view('events.edit', compact('event', 'venues'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'venue_id' => 'required|exists:venues,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'expected_attendance' => 'required|integer|min:1'
        ]);

        $event->update($request->all());
        
        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        foreach ($event->documents as $document) {
            Storage::delete($document->path);
            $document->delete();
        }
        
        $event->delete();
        
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Upload documents for an event
     */
    public function uploadDocuments(Request $request, Event $event)
    {
        $request->validate([
            'documents.*' => 'required|mimes:pdf,doc,docx|max:10240'
        ]);

        foreach ($request->file('documents') as $document) {
            $path = $document->store('event-documents');
            $event->documents()->create([
                'path' => $path,
                'name' => $document->getClientOriginalName()
            ]);
        }

        return back()->with('success', 'Documents uploaded successfully.');
    }

    /**
     * Show pending event approvals for OCA
     */
    public function pendingApprovals()
    {
        $events = Event::where('status', 'pending')
            ->with(['club', 'venue'])
            ->latest()
            ->paginate(10);
            
        return view('events.pending', compact('events'));
    }

    /**
     * Approve an event
     */
    public function approve(Event $event)
    {
        $event->update(['status' => 'approved']);
        $event->club->notify(new EventApproved($event));

        return back()->with('success', 'Event has been approved.');
    }

    /**
     * Reject an event
     */
    public function reject(Request $request, Event $event)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $event->club->notify(new EventRejected($event));

        return back()->with('success', 'Event has been rejected.');
    }
}
