<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Department;
use App\Notifications\TaskNotification;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // If user has role 5, show all tasks, otherwise show only assigned tasks
        if ($user->role == 5) {
            $tasks = Task::with(['assignedBy', 'assignedTo'])->get();
        } else {
            $tasks = Task::with(['assignedBy', 'assignedTo'])
                         ->where('assigned_to', $user->id)
                         ->get();
        }
        
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::all();
        $departments = Department::all();
        return view('tasks.create', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_by' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'deadline' => 'nullable|date',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $taskData = $request->only([
            'title', 'description', 'assigned_by', 'assigned_to', 
            'department_id', 'priority', 'status', 'deadline'
        ]);

        if ($request->hasFile('attachment')) {
            $taskData['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $task = Task::create($taskData);

        // Send notification to assigned user if task is assigned
        if ($task->assigned_to && $task->assignedTo) {
            $task->assignedTo->notify(new TaskNotification($task, 'created', auth()->user()));
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $users = User::all();
        $departments = Department::all();
        return view('tasks.edit', compact('task', 'users', 'departments'));
    }

    public function update(Request $request, Task $task)
    {
        $user = auth()->user();
        $originalAssignedTo = $task->assigned_to;
        $originalStatus = $task->status;

        if ($user->role == 5) { // Admin/CEO role
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'assigned_by' => 'required|exists:users,id',
                'assigned_to' => 'nullable|exists:users,id',
                'department_id' => 'nullable|exists:departments,id',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:pending,in_progress,completed,on_hold',
                'deadline' => 'nullable|date',
                'attachment' => 'nullable|file|max:10240',
            ]);

            $task->fill($request->only([
                'title', 'description', 'assigned_by', 'assigned_to', 
                'department_id', 'priority', 'status', 'deadline'
            ]));
        } else { // All other roles (including employees)
            $request->validate([
                'status' => 'required|in:pending,in_progress,completed,on_hold',
                'attachment' => 'nullable|file|max:10240',
            ]);

            $task->status = $request->status;
        }

        // Handle attachment for all roles
        if ($request->hasFile('attachment')) {
            if ($task->attachment) {
                \Storage::disk('public')->delete($task->attachment);
            }
            $task->attachment = $request->file('attachment')->store('attachments', 'public');
        }

        $task->save();

        // Send notifications after saving
        $this->sendTaskUpdateNotifications($task, $originalAssignedTo, $originalStatus, $user);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if ($task->attachment) {
            \Storage::disk('public')->delete($task->attachment);
        }
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $originalStatus = $task->status;
        
        $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,on_hold,cancelled',
        ]);

        $task->update(['status' => $request->status]);

        // Send notification if status changed and task is assigned
        if ($originalStatus !== $request->status && $task->assigned_to && $task->assignedTo) {
            $task->assignedTo->notify(new TaskNotification($task, 'status_updated', auth()->user()));
        }

        return redirect()->back()->with('success', 'Task status updated successfully!');
    }

    /**
     * Handle notifications for task updates
     */
    private function sendTaskUpdateNotifications($task, $originalAssignedTo, $originalStatus, $actionBy)
    {
        // If task was reassigned to a different user
        if ($originalAssignedTo !== $task->assigned_to) {
            // Notify new assignee if task is now assigned to someone
            if ($task->assigned_to && $task->assignedTo) {
                $task->assignedTo->notify(new TaskNotification($task, 'created', $actionBy));
            }
        } else {
            // Task updated for same user
            if ($task->assigned_to && $task->assignedTo) {
                // Check if it's just a status update or full update
                $action = ($originalStatus !== $task->status && $actionBy->id !== $task->assigned_to) 
                    ? 'status_updated' 
                    : 'updated';
                    
                $task->assignedTo->notify(new TaskNotification($task, $action, $actionBy));
            }
        }
    }
}