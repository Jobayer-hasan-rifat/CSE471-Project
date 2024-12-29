<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClubMembersExport;
use App\Imports\ClubMembersImport;
use Carbon\Carbon;

class ClubController extends Controller
{
    /**
     * Display club members
     */
    public function members()
    {
        $club = Club::where('email', Auth::user()->email)->first();
        $members = $club->members()->paginate(10);
        
        return view('club.members.index', compact('members'));
    }

    /**
     * Add a new club member
     */
    public function addMember(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'required|string|unique:users,student_id',
            'role' => 'required|string|in:member,executive'
        ]);

        $club = Club::where('email', Auth::user()->email)->first();
        
        $member = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'password' => Hash::make('default123'), // Set a default password
            'role' => 'club_member'
        ]);

        $club->members()->attach($member->id, ['role' => $request->role]);

        return back()->with('success', 'Member added successfully.');
    }

    /**
     * Remove a club member
     */
    public function removeMember(User $member)
    {
        $club = Club::where('email', Auth::user()->email)->first();
        $club->members()->detach($member->id);
        
        return back()->with('success', 'Member removed successfully.');
    }

    /**
     * Import club members from Excel/CSV
     */
    public function importMembers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $club = Club::where('email', Auth::user()->email)->first();
        
        Excel::import(new ClubMembersImport($club), $request->file('file'));

        return back()->with('success', 'Members imported successfully.');
    }

    /**
     * Export club members to Excel
     */
    public function exportMembers()
    {
        $club = Club::where('email', Auth::user()->email)->first();
        
        return Excel::download(new ClubMembersExport($club), 'club-members.xlsx');
    }

    /**
     * Display club activity log
     */
    public function activityLog()
    {
        $club = Club::where('email', Auth::user()->email)->first();
        
        $activities = Event::where('club_id', $club->id)
            ->with('venue')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $stats = [
            'total_events' => $club->events()->count(),
            'approved_events' => $club->events()->where('status', 'approved')->count(),
            'pending_events' => $club->events()->where('status', 'pending')->count(),
            'rejected_events' => $club->events()->where('status', 'rejected')->count(),
        ];
        
        return view('club.activity-log', compact('activities', 'stats'));
    }

    /**
     * Generate club activity reports
     */
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonth());
        $endDate = $request->get('end_date', Carbon::now());
        
        $clubStats = Club::withCount(['events' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();
        
        $eventStatusByClub = Event::whereBetween('created_at', [$startDate, $endDate])
            ->with('club')
            ->get()
            ->groupBy('club.name')
            ->map(function ($events) {
                return [
                    'total' => $events->count(),
                    'approved' => $events->where('status', 'approved')->count(),
                    'rejected' => $events->where('status', 'rejected')->count(),
                    'pending' => $events->where('status', 'pending')->count(),
                ];
            });
            
        $monthlyActivity = Event::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('reports.clubs', compact('clubStats', 'eventStatusByClub', 'monthlyActivity'));
    }

    /**
     * Display a listing of the clubs.
     */
    public function index()
    {
        $clubs = Club::all();

        if(request()->ajax()) {
            return view('clubs.index', compact('clubs'))->render();
        }

        return view('clubs.index', compact('clubs'));
    }

    /**
     * Display the specified club.
     */
    public function show(Club $club)
    {
        if(request()->ajax()) {
            return view('clubs.show', compact('club'))->render();
        }

        return view('clubs.show', compact('club'));
    }
}
