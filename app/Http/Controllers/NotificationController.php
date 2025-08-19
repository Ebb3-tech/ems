<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\NotificationReply;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if ($user->isCEO()) {
        // CEO sees all notifications they have sent
        $notifications = Notification::with(['sender', 'user'])
            ->where('created_by', $user->id)
            ->latest()
            ->get();
    } else {
        // Other users see only notifications sent to them
        $notifications = Notification::with(['sender', 'user'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    return view('notifications.index', compact('notifications'));
}

public function reply(Request $request, Notification $notification)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // current user is sender of this reply
        $senderId = Auth::id();

        // the original notification sender is the receiver of this reply
        $receiverId = $notification->created_by;

        // Prevent replying to self
        if ($senderId == $receiverId) {
            return back()->withErrors(['message' => 'You cannot reply to yourself.']);
        }

        NotificationReply::create([
            'notification_id' => $notification->id,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        return redirect()->route('notifications.show', $notification)->with('success', 'Reply sent successfully.');
    }


    public function create()
    {
        $users = User::all();
        return view('notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $validated['created_by'] = Auth::id();

        Notification::create($validated);

        return redirect()->route('notifications.index')->with('success', 'Notification created and assigned successfully.');
    }

    public function show(Notification $notification)
    {
        return view('notifications.show', compact('notification'));
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
    
}
