<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClubChat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClubChatController extends Controller
{
    public function index()
    {
        $ocaUser = User::role('oca')->first();
        if (!$ocaUser) {
            abort(404, 'OCA user not found');
        }

        return view('club.chat.index', compact('ocaUser'));
    }

    public function getMessages()
    {
        $ocaUser = User::role('oca')->first();
        if (!$ocaUser) {
            abort(404, 'OCA user not found');
        }

        $messages = ClubChat::with(['sender', 'receiver'])
            ->where(function($query) use ($ocaUser) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $ocaUser->id);
            })
            ->orWhere(function($query) use ($ocaUser) {
                $query->where('sender_id', $ocaUser->id)
                    ->where('receiver_id', Auth::id());
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
            'content' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:10240' // 10MB max
        ]);

        $ocaUser = User::role('oca')->first();
        if (!$ocaUser) {
            abort(404, 'OCA user not found');
        }

        $message = new ClubChat();
        $message->sender_id = Auth::id();
        $message->receiver_id = $ocaUser->id;
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
