<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // Get all club representatives
        $clubs = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['bucc', 'buac', 'robu', 'bizbee']);
        })
        ->with(['roles', 'club']) // Load club relationship
        ->get();

        // Get the latest message from each conversation
        $conversations = Chat::where(function($query) {
            $query->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
        })
        ->with(['sender.club', 'receiver.club']) // Load club relationships
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function($chat) {
            return $chat->sender_id == Auth::id() 
                ? $chat->receiver_id 
                : $chat->sender_id;
        })
        ->map(function($chats) {
            return $chats->first();
        });

        return view('oca.chat.index', compact('clubs', 'conversations'));
    }

    public function show($userId)
    {
        $otherUser = User::with(['roles', 'club']) // Load club relationship
            ->findOrFail($userId);
        
        // Mark messages as read
        Chat::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        // Get chat history
        $messages = Chat::where(function($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', Auth::id());
        })
        ->with(['sender.club', 'receiver.club']) // Load club relationships
        ->orderBy('created_at', 'asc')
        ->get();

        return view('oca.chat.show', compact('messages', 'otherUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'read' => false
        ]);

        return redirect()->back()->with('success', 'Message sent successfully');
    }

    public function getUnreadCount()
    {
        $count = Chat::where('receiver_id', Auth::id())
                    ->where('read', false)
                    ->count();

        return response()->json(['count' => $count]);
    }
}
