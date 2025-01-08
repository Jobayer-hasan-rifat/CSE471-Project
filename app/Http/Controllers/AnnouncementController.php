<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display OCA announcements for club users.
     */
    public function clubAnnouncements()
    {
        // For club users, show only OCA announcements (where club_id is null)
        $announcements = Announcement::whereNull('club_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('club.announcements.index', compact('announcements'));
    }

    /**
     * Display a listing of announcements for OCA.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(10);
        return view('oca.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('oca.announcements.create');
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'club_id' => 'nullable|exists:clubs,id'
        ]);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'club_id' => $request->filled('club_id') ? $validated['club_id'] : null
        ]);

        return redirect()->route('oca.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('oca.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Show the form for editing an announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('oca.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'club_id' => 'nullable|exists:clubs,id'
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'club_id' => $request->filled('club_id') ? $validated['club_id'] : null
        ]);

        return redirect()->route('oca.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }
}
