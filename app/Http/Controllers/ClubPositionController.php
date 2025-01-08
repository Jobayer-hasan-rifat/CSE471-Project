<?php

namespace App\Http\Controllers;

use App\Models\ClubPosition;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClubPositionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'position_name' => 'required|string|max:255',
                'member_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048', // 2MB max
            ]);

            $club = Club::where('user_id', auth()->id())->firstOrFail();

            $position = new ClubPosition();
            $position->club_id = $club->id;
            $position->position_name = $validated['position_name'];
            $position->member_name = $validated['member_name'];
            $position->email = $validated['email'];
            $position->phone = $validated['phone'];
            $position->description = $validated['description'];

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('club-positions', 'public');
                $position->image_path = $path;
            }

            $position->save();

            return response()->json([
                'success' => true,
                'message' => 'Position added successfully',
                'position' => $position
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding position: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(ClubPosition $position)
    {
        try {
            // Check if the position belongs to the current user's club
            $club = Club::where('user_id', auth()->id())->firstOrFail();
            if ($position->club_id !== $club->id) {
                throw new \Exception('Unauthorized');
            }

            return response()->json($position);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching position: ' . $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, ClubPosition $position)
    {
        try {
            // Check if the position belongs to the current user's club
            $club = Club::where('user_id', auth()->id())->firstOrFail();
            if ($position->club_id !== $club->id) {
                throw new \Exception('Unauthorized');
            }

            $validated = $request->validate([
                'position_name' => 'required|string|max:255',
                'member_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);

            $position->position_name = $validated['position_name'];
            $position->member_name = $validated['member_name'];
            $position->email = $validated['email'];
            $position->phone = $validated['phone'];
            $position->description = $validated['description'];

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($position->image_path) {
                    Storage::disk('public')->delete($position->image_path);
                }
                
                $path = $request->file('image')->store('club-positions', 'public');
                $position->image_path = $path;
            }

            $position->save();

            return response()->json([
                'success' => true,
                'message' => 'Position updated successfully',
                'position' => $position
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating position: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ClubPosition $position)
    {
        try {
            // Check if the position belongs to the current user's club
            $club = Club::where('user_id', auth()->id())->firstOrFail();
            if ($position->club_id !== $club->id) {
                throw new \Exception('Unauthorized');
            }

            if ($position->image_path) {
                Storage::disk('public')->delete($position->image_path);
            }

            $position->delete();

            return response()->json([
                'success' => true,
                'message' => 'Position deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting position: ' . $e->getMessage()
            ], 500);
        }
    }
}
