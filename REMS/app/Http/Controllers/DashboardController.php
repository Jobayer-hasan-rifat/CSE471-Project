<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Club;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('oca')) {
            return $this->ocaDashboard();
        } elseif ($user->hasRole('club')) {
            return $this->clubDashboard();
        } elseif ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }
        
        return redirect()->route('login');
    }

    /**
     * Display OCA dashboard
     */
    public function ocaDashboard()
    {
        $pendingEvents = Event::where('status', 'pending')->count();
        $approvedEvents = Event::where('status', 'approved')->count();
        $totalVenues = Venue::count();
        $upcomingEvents = Event::where('status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();
        
        return view('dashboards.oca', compact(
            'pendingEvents',
            'approvedEvents',
            'totalVenues',
            'upcomingEvents'
        ));
    }

    /**
     * Display Club dashboard
     */
    public function clubDashboard()
    {
        $user = Auth::user();
        $club = Club::where('email', $user->email)->first();
        
        $myEvents = Event::where('club_id', $club->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $pendingEvents = Event::where('club_id', $club->id)
            ->where('status', 'pending')
            ->count();
            
        $approvedEvents = Event::where('club_id', $club->id)
            ->where('status', 'approved')
            ->count();
            
        $rejectedEvents = Event::where('club_id', $club->id)
            ->where('status', 'rejected')
            ->count();

        return view('dashboards.club', compact(
            'myEvents',
            'pendingEvents',
            'approvedEvents',
            'rejectedEvents'
        ));
    }

    /**
     * Display Admin dashboard
     */
    public function adminDashboard()
    {
        $totalClubs = Club::count();
        $totalEvents = Event::count();
        $totalVenues = Venue::count();
        $activeUsers = User::where('last_login', '>=', now()->subDays(30))->count();
        
        $recentActivity = Event::with(['club'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboards.admin', compact(
            'totalClubs',
            'totalEvents',
            'totalVenues',
            'activeUsers',
            'recentActivity'
        ));
    }
}
