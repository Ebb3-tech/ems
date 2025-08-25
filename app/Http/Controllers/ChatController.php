<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();

    // All other users
    $users = \App\Models\User::where('id', '!=', $user->id)->get();

    $receiverId = $request->receiver_id;

    $messages = [];
    if ($receiverId) {
        $messages = Chat::with('sender', 'receiver')
            ->where(function ($q) use ($user, $receiverId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($user, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    return view('chat.index', compact('users', 'messages', 'receiverId'));
}




   public function send(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'message' => 'required|string',
    ]);

    $chat = Chat::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'message' => $request->message,
    ]);

    broadcast(new MessageSent($chat));

    return redirect()->route('chat.index', ['receiver_id' => $request->receiver_id]);
}


}
