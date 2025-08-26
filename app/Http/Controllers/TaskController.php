<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['assignedBy', 'assignedTo'])->get();
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

        Task::create($taskData);

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

    if ($user->role == 1) { // Employee role
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,on_hold',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $task->status = $request->status;

        if ($request->hasFile('attachment')) {
            if ($task->attachment) {
                \Storage::disk('public')->delete($task->attachment);
            }
            $task->attachment = $request->file('attachment')->store('attachments', 'public');
        }
    } else {
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

        if ($request->hasFile('attachment')) {
            if ($task->attachment) {
                \Storage::disk('public')->delete($task->attachment);
            }
            $task->attachment = $request->file('attachment')->store('attachments', 'public');
        }
    }

    $task->save();

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
    $request->validate([
        'status' => 'required|in:not_started,in_progress,completed,on_hold,cancelled',
    ]);

    $task->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'Task status updated successfully!');
}

}
