<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('oca')) {
            return redirect()->route('oca.dashboard');
        } elseif ($user->hasRole(['bucc', 'robu', 'bizbee', 'buedf'])) {
            return redirect()->route('club.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('dashboard');
    }

    public function ocaDashboard()
    {
        // Get counts
        $totalClubs = Club::count();
        $totalEvents = Event::count();
        $pendingEvents = Event::where('status', 'pending')->count();
        $totalVenues = Venue::count();

        // Get recent and upcoming events
        $recentEvents = Event::with('club')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingEvents = Event::with('club')
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        return view('dashboards.oca', compact(
            'totalClubs',
            'totalEvents',
            'pendingEvents',
            'totalVenues',
            'recentEvents',
            'upcomingEvents'
        ));
    }

    public function clubDashboard()
    {
        $user = Auth::user();
        $club = $user->club;

        // Get total members
        $totalMembers = $club->members()->count();

        // Get events count
        $totalEvents = $club->events()->count();
        $approvedEvents = $club->events()->where('status', 'approved')->count();
        $pendingEvents = $club->events()->where('status', 'pending')->count();

        // Get current fiscal year's budget
        $currentYear = Carbon::now()->year;
        $budget = $club->budgets()
            ->where('fiscal_year', $currentYear)
            ->first();

        $totalBudget = $budget ? $budget->amount : 0;
        $totalExpenses = $club->transactions()
            ->where('type', 'expense')
            ->whereYear('date', $currentYear)
            ->sum('amount');
        $remainingBudget = $totalBudget - $totalExpenses;

        // Get recent transactions
        $recentTransactions = $club->transactions()
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Get upcoming events
        $upcomingEvents = $club->events()
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        return view('dashboards.club', compact(
            'club',
            'totalMembers',
            'totalEvents',
            'approvedEvents',
            'pendingEvents',
            'totalBudget',
            'totalExpenses',
            'remainingBudget',
            'recentTransactions',
            'upcomingEvents'
        ));
    }

    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalClubs = Club::count();
        $totalEvents = Event::count();
        
        return view('dashboards.admin', compact(
            'totalUsers',
            'totalClubs',
            'totalEvents'
        ));
    }
}
