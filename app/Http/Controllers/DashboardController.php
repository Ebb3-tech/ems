<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use App\Models\Task;
use App\Models\LeaveRequest;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function show()
{
    $user = auth()->user();

    if ($user->isCEO()) {
        $departmentsCount = Department::count();
        $usersCount = User::count();
        $tasksCount = Task::count();
        $leaveRequestsCount = LeaveRequest::count();

        return view('dashboard_ceo', compact(
            'departmentsCount',
            'usersCount',
            'tasksCount',
            'leaveRequestsCount'
        ));
    } else {
        $tasks = $user->assignedTasks()->latest()->take(5)->get();
        $notifications = $user->notifications()->latest()->get();

        return view('dashboard_employee', compact('tasks', 'notifications'));
    }
}

    public function storeNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notification::create([
            'title'      => $request->title,
            'message'    => $request->message,
            'created_by' => auth()->id(),
            'user_id'    => $request->user_id,
        ]);

        return redirect()->back()->with('success', 'Notification sent successfully.');
    }
}
