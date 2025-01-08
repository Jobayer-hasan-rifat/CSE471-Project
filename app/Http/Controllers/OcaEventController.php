<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class OcaEventController extends Controller
{
    public function index()
    {
        if (!Gate::allows('viewAny', Event::class)) {
            abort(403);
        }

        $events = Event::with(['club', 'venue'])
            ->where('status', 'pending')
            ->orderBy('start_date')
            ->paginate(10);

        return view('oca.events.pending', compact('events'));
    }

    public function show(Event $event)
    {
        if (!Gate::allows('view', $event)) {
            abort(403);
        }

        return view('oca.events.show', compact('event'));
    }

    public function approve(Event $event)
    {
        if (!Gate::allows('approve', $event)) {
            abort(403);
        }

        try {
            $event->update(['status' => 'approved']);

            // Notify club members
            $clubMembers = $event->club->members;
            if ($clubMembers->isNotEmpty()) {
                Notification::send($clubMembers, new EventStatusChanged($event, 'approved'));
            }

            return redirect()
                ->route('oca.events.index')
                ->with('success', 'Event approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve event: ' . $e->getMessage());
        }
    }

    public function reject(Event $event)
    {
        if (!Gate::allows('reject', $event)) {
            abort(403);
        }

        try {
            $event->update(['status' => 'rejected']);

            // Notify club members
            $clubMembers = $event->club->members;
            if ($clubMembers->isNotEmpty()) {
                Notification::send($clubMembers, new EventStatusChanged($event, 'rejected'));
            }

            return redirect()
                ->route('oca.events.index')
                ->with('success', 'Event rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject event: ' . $e->getMessage());
        }
    }
}
