<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use App\Models\User;
use App\Models\Club;
use App\Notifications\EventCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        if (!Gate::allows('viewAny', Event::class)) {
            abort(403);
        }

        $user = Auth::user();
        $events = Event::with(['club', 'venue'])
            ->when($user->hasRole('club'), function ($query) use ($user) {
                return $query->where('club_id', $user->club->id);
            })
            ->latest()
            ->paginate(10);

        return view('club.events.index', compact('events'));
    }

    public function create()
    {
        if (!Gate::allows('create', Event::class)) {
            abort(403);
        }

        $venues = Venue::all();
        return view('club.events.create', compact('venues'));
    }

    public function show(Event $event)
    {
        if (!Gate::allows('view', $event)) {
            abort(403);
        }

        return view('club.events.show', compact('event'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('create', Event::class)) {
            abort(403);
        }

        try {
            // Validate the request
            $validated = $request->validate([
                'event_name' => 'required|string|max:255',
                'description' => 'required|string',
                'venue_id' => 'required|exists:venues,id',
                'start_date' => ['required', 'date', function ($attribute, $value, $fail) {
                    $startDate = Carbon::parse($value);
                    if ($startDate->isPast()) {
                        $fail('The start date must be in the future.');
                    }
                }],
                'end_date' => ['required', 'date', function ($attribute, $value, $fail) use ($request) {
                    if (!$request->has('start_date')) {
                        return;
                    }
                    $startDate = Carbon::parse($request->start_date);
                    $endDate = Carbon::parse($value);
                    if ($endDate->lte($startDate)) {
                        $fail('The end date must be after the start date.');
                    }
                }],
                'expected_attendance' => 'required|integer|min:1',
                'event_type' => 'required|string|in:workshop,seminar,competition,cultural,other',
                'budget' => 'required|numeric|min:0',
                'requirements' => 'nullable|string'
            ]);

            DB::beginTransaction();

            // Get the authenticated user's club
            $user = Auth::user();
            $club = $user->club;

            if (!$club) {
                throw new \Exception('You must be associated with a club to create events.');
            }

            // Create the event
            $event = Event::create([
                'event_name' => $validated['event_name'],
                'description' => $validated['description'],
                'venue_id' => $validated['venue_id'],
                'club_id' => $club->id,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'expected_attendance' => $validated['expected_attendance'],
                'event_type' => $validated['event_type'],
                'budget' => $validated['budget'],
                'requirements' => $validated['requirements'] ?? null,
                'status' => 'pending'
            ]);

            // Temporarily disable notifications
            // $ocaUsers = User::role('oca')->get();
            // if ($ocaUsers->isNotEmpty()) {
            //     Notification::send($ocaUsers, new EventCreated($event));
            // }

            DB::commit();

            return redirect()
                ->route('club.events.index')
                ->with('success', 'Event created successfully and is pending approval.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create event', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function approve(Event $event)
    {
        if (!Gate::allows('approve', $event)) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            if (!$event->isPending()) {
                throw new \Exception('This event cannot be approved.');
            }

            $event->update(['status' => Event::STATUS_APPROVED]);
            
            // Notify the club
            $event->club->user->notify(new EventStatusChanged($event, 'approved'));

            DB::commit();

            return back()->with('success', 'Event has been approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve event', [
                'error' => $e->getMessage(),
                'event_id' => $event->id,
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'Failed to approve event: ' . $e->getMessage());
        }
    }

    public function reject(Event $event)
    {
        if (!Gate::allows('reject', $event)) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            if (!$event->isPending()) {
                throw new \Exception('This event cannot be rejected.');
            }

            $event->update(['status' => Event::STATUS_REJECTED]);
            
            // Notify the club
            $event->club->user->notify(new EventStatusChanged($event, 'rejected'));

            DB::commit();

            return back()->with('success', 'Event has been rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject event', [
                'error' => $e->getMessage(),
                'event_id' => $event->id,
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'Failed to reject event: ' . $e->getMessage());
        }
    }

    public function pendingApprovals()
    {
        if (!Gate::allows('viewAny', Event::class)) {
            abort(403);
        }

        $events = Event::with(['club', 'venue'])
            ->pending()
            ->latest()
            ->paginate(10);

        return view('oca.pending-events', compact('events'));
    }
}
