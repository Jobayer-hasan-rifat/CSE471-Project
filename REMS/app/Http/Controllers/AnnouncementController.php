<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $announcement = new Announcement();
        $announcement->title = $validated['title'];
        $announcement->content = $validated['content'];
        $announcement->user_id = Auth::id();
        $announcement->save();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    public function filter($type)
    {
        $announcements = Announcement::with('user');

        if ($type === 'ours') {
            $announcements = $announcements->where('user_id', Auth::id());
        }

        $announcements = $announcements->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }
}
