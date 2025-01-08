<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('oca')) {
            return $this->ocaDashboard();
        } elseif ($user->hasRole('club')) {
            return $this->clubDashboard();
        }

        return abort(403, 'User does not have the right roles.');
    }

    /**
     * Show the admin dashboard.
     */
    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalClubs = Club::count();
        $activeClubs = Club::where('status', '=', 'active')->count();
        $inactiveClubs = Club::where('status', '!=', 'active')->count();
        $totalEvents = Event::count();
        $pendingEvents = Event::where('status', 'pending')->count();
        $approvedEvents = Event::where('status', 'approved')->count();
        $rejectedEvents = Event::where('status', 'rejected')->count();
        $totalVenues = Venue::count();
        $totalBudget = Event::where('status', 'approved')->sum('budget');
        $recentEvents = Event::with('club')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.admin', compact(
            'totalUsers',
            'totalClubs',
            'activeClubs',
            'inactiveClubs',
            'totalEvents',
            'pendingEvents',
            'approvedEvents',
            'rejectedEvents',
            'totalVenues',
            'totalBudget',
            'recentEvents'
        ));
    }

    /**
     * Show the OCA dashboard.
     */
    public function ocaDashboard()
    {
        // Get total events and budget
        $totalEvents = Event::count();
        $totalBudget = Event::where('status', 'approved')->sum('budget');
        $totalClubs = Club::count();

        // Get event distribution
        $eventDistribution = [
            'pending' => Event::where('status', 'pending')->count(),
            'approved' => Event::where('status', 'approved')->count(),
            'rejected' => Event::where('status', 'rejected')->count(),
        ];

        // Get pending events with pagination
        $pendingEvents = Event::with(['club', 'venue'])
            ->where('status', 'pending')
            ->orderBy('start_date')
            ->paginate(10);

        // Get recent events
        $recentEvents = Event::with('club')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get monthly event data
        $monthlyEvents = Event::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $eventCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('F', mktime(0, 0, 0, $i, 1));
            $count = $monthlyEvents->firstWhere('month', $i);
            $eventCounts[] = $count ? $count->count : 0;
        }

        $chartData = [
            'months' => $months,
            'eventCounts' => $eventCounts,
        ];

        return view('oca.dashboard', compact(
            'totalEvents',
            'totalBudget',
            'totalClubs',
            'eventDistribution',
            'pendingEvents',
            'recentEvents',
            'chartData'
        ));
    }

    /**
     * Get the OCA dashboard content.
     */
    public function ocaDashboardContent()
    {
        // Get total events and budget
        $totalEvents = Event::count();
        $totalBudget = Event::where('status', 'approved')->sum('budget');
        $totalClubs = Club::count();

        // Get event distribution
        $eventDistribution = [
            'pending' => Event::where('status', 'pending')->count(),
            'approved' => Event::where('status', 'approved')->count(),
            'rejected' => Event::where('status', 'rejected')->count(),
        ];

        // Get pending events
        $pendingEvents = Event::with(['club', 'venue'])
            ->where('status', 'pending')
            ->orderBy('start_date')
            ->get();

        // Get recent events
        $recentEvents = Event::with('club')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get monthly event data for the current year
        $monthlyEvents = Event::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $eventCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('F', mktime(0, 0, 0, $i, 1));
            $count = $monthlyEvents->firstWhere('month', $i);
            $eventCounts[] = $count ? $count->count : 0;
        }

        $chartData = [
            'months' => $months,
            'eventCounts' => $eventCounts,
        ];

        return view('oca.dashboard-content', compact(
            'totalEvents',
            'totalBudget',
            'totalClubs',
            'eventDistribution',
            'pendingEvents',
            'recentEvents',
            'chartData'
        ));
    }

    /**
     * Show the club dashboard.
     */
    public function clubDashboard()
    {
        $user = Auth::user();
        $club = $user->club;

        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }

        $stats = [
            'total_events' => $club->events()->count(),
            'approved_events' => $club->events()->where('status', 'approved')->count(),
            'pending_events' => $club->events()->where('status', 'pending')->count(),
            'rejected_events' => $club->events()->where('status', 'rejected')->count(),
        ];

        $recentEvents = $club->events()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingEvents = $club->events()
            ->where('start_date', '>', now())
            ->where('status', 'approved')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // Get recent announcements
        $announcements = Announcement::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('club.dashboard', compact(
            'club',
            'stats',
            'recentEvents',
            'upcomingEvents',
            'announcements'
        ));
    }

    /**
     * Show the club chat.
     */
    public function clubChat()
    {
        $user = Auth::user();
        $club = $user->club;

        if (!$club) {
            abort(403, 'You are not associated with any club.');
        }

        $clubs = Club::where('id', '!=', $club->id)->get();

        return view('club.chat.index', compact('club', 'clubs'));
    }
}
