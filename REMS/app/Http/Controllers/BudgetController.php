<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Club;

class BudgetController extends Controller
{
    public function index()
    {
        $totalBudget = Event::sum('budget');
        $budgetByClub = Event::selectRaw('club_id, SUM(budget) as total_budget')
            ->groupBy('club_id')
            ->with('club')
            ->get();

        return view('budget.index', compact('totalBudget', 'budgetByClub'));
    }

    public function distribution()
    {
        $budgetDistribution = Event::selectRaw('club_id, SUM(budget) as total_budget')
            ->groupBy('club_id')
            ->with('club')
            ->get();

        return view('budget.distribution', compact('budgetDistribution'));
    }

    public function reports()
    {
        $monthlyBudgets = Event::selectRaw('MONTH(created_at) as month, SUM(budget) as total_budget')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('budget.reports', compact('monthlyBudgets'));
    }

    public function statistics()
    {
        $totalBudget = Event::sum('budget');
        $averageBudget = Event::avg('budget');
        $maxBudget = Event::max('budget');
        $minBudget = Event::min('budget');

        return view('budget.statistics', compact('totalBudget', 'averageBudget', 'maxBudget', 'minBudget'));
    }

    public function clubReports()
    {
        $club = auth()->user()->club;
        $clubBudget = Event::where('club_id', $club->id)
            ->selectRaw('MONTH(created_at) as month, SUM(budget) as total_budget')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('budget.club-reports', compact('clubBudget'));
    }
}
