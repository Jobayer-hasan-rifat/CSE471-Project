<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClubChat;
use App\Models\Club;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OcaChatController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $clubs = Club::with('user')->active()->get();
        return view('oca.chat.index', compact('clubs'));
    }

    public function show(Club $club)
    {
        $clubs = Club::with('user')->active()->get();
        return view('oca.chat.show', compact('clubs', 'club'));
    }

    public function getMessages(Club $club)
    {
        $ocaUser = Auth::user();
        $clubUser = $club->user;

        $messages = ClubChat::with(['sender', 'receiver'])
            ->where(function($query) use ($ocaUser, $clubUser) {
                $query->where('sender_id', $ocaUser->id)
                    ->where('receiver_id', $clubUser->id);
            })
            ->orWhere(function($query) use ($ocaUser, $clubUser) {
                $query->where('sender_id', $clubUser->id)
                    ->where('receiver_id', $ocaUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name
                    ],
                    'content' => $message->content,
                    'attachment' => $message->attachment,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s')
                ];
            });

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'content' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:10240' // 10MB max
        ]);

        $club = Club::findOrFail($request->club_id);
        $message = new ClubChat();
        $message->sender_id = Auth::id();
        $message->receiver_id = $club->user_id;
        $message->content = $request->content;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat-attachments', 'public');
            $message->attachment = $path;
        }

        $message->save();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name
                ],
                'content' => $message->content,
                'attachment' => $message->attachment,
                'created_at' => $message->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
