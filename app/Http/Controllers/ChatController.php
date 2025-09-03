<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $receiverId = $request->receiver_id;
        
        // Get unread message counts per user
        $unreadCounts = Chat::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->select('sender_id', DB::raw('count(*) as count'))
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id')
            ->toArray();
        
        $messages = [];
        if ($receiverId) {
            $messages = Chat::where(function($query) use ($receiverId) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $receiverId);
            })->orWhere(function($query) use ($receiverId) {
                $query->where('sender_id', $receiverId)
                      ->where('receiver_id', auth()->id());
            })->orderBy('created_at')->get();
            
            // Mark messages as read
            Chat::where('sender_id', $receiverId)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        
        return view('chat.index', compact('users', 'receiverId', 'messages', 'unreadCounts'));
    }




   public function send(Request $request)
{
    $request->validate([
    'receiver_id' => 'required|exists:users,id',
    'message' => 'nullable|string',
    'file' => 'nullable|file|max:20480', // 20MB max
]);



    $filePath = null;
    $fileType = null;

    if ($request->hasFile('file')) {
    $filePath = $request->file('file')->store('chat_files', 'public');
    $extension = strtolower($request->file('file')->extension());

    if (in_array($extension, ['jpg','jpeg','png','gif'])) {
        $fileType = 'image';
    } elseif (in_array($extension, ['mp3','wav','m4a','webm'])) {
        $fileType = 'audio';
    } elseif (in_array($extension, ['pdf'])) {
        $fileType = 'pdf';
    } elseif (in_array($extension, ['doc','docx'])) {
        $fileType = 'word';
    } elseif (in_array($extension, ['xls','xlsx'])) {
        $fileType = 'excel';
    } else {
        $fileType = 'file'; // fallback for other types
    }
}



    $chat = Chat::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'message' => $request->message ?? null,
        'file_path' => $filePath,
        'file_type' => $fileType,
    ]);

    broadcast(new MessageSent($chat));

    return redirect()->route('chat.index', ['receiver_id' => $request->receiver_id]);
}



}
