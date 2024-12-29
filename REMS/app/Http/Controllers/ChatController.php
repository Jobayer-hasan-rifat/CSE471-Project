<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $clubs = Club::select('id', 'name', 'email', 'logo')
            ->get()
            ->map(function ($club) {
                // If no logo, use first two letters of name
                if (!$club->logo) {
                    $club->initial = strtoupper(substr($club->name, 0, 2));
                }
                return $club;
            });

        if(request()->ajax()) {
            return view('chat.index', compact('clubs'))->render();
        }

        return view('chat.index', compact('clubs'));
    }

    public function getMessages($clubId)
    {
        $messages = Message::where('club_id', $clubId)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'content' => $message->content,
                    'is_sender' => $message->user_id === Auth::id(),
                    'user_name' => $message->user->name,
                    'created_at' => $message->created_at
                ];
            });

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'club_id' => $validated['club_id'],
            'content' => $validated['message']
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'content' => $message->content,
                'is_sender' => true,
                'user_name' => Auth::user()->name,
                'created_at' => $message->created_at
            ]
        ]);
    }
}
