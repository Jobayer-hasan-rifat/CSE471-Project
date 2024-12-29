<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\ClubMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;
use App\Imports\MembersImport;

class MemberController extends Controller
{
    /**
     * Display a listing of club members
     */
    public function index()
    {
        $user = Auth::user();
        
        // Find the club associated with the current user
        $club = Club::where('email', $user->email)->first();
        
        if (!$club) {
            return redirect()->back()->with('error', 'Club not found');
        }
        
        // Get club members with their user details
        $members = ClubMember::where('club_id', $club->id)
            ->with('user')
            ->paginate(10);
        
        return view('members.index', compact('members', 'club'));
    }

    /**
     * Export club members to Excel
     */
    public function export()
    {
        $user = Auth::user();
        $club = Club::where('email', $user->email)->first();
        
        if (!$club) {
            return redirect()->back()->with('error', 'Club not found');
        }
        
        return Excel::download(new MembersExport($club->id), 'club_members.xlsx');
    }

    /**
     * Import club members from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        
        $user = Auth::user();
        $club = Club::where('email', $user->email)->first();
        
        if (!$club) {
            return redirect()->back()->with('error', 'Club not found');
        }
        
        try {
            Excel::import(new MembersImport($club->id), $request->file('file'));
            
            return redirect()->route('members.index')
                ->with('success', 'Members imported successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing members: ' . $e->getMessage());
        }
    }
}
