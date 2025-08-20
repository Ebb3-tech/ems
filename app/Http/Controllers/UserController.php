<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Display all users
    public function index()
    {
        $users = User::with('department')->get();
        return view('users.index', compact('users'));
    }

    // Show form to create a new user
    public function create()
    {
        $departments = Department::all();
        return view('users.create', compact('departments'));
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'role' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $userData = $request->only('name', 'email', 'department_id', 'position', 'role');
        $userData['password'] = Hash::make($request->password);

        User::create($userData);

        return redirect()->route('users.index')->with('success', 'Employee created successfully.');
    }

    // Show form to edit an existing user
    public function edit(User $user)
    {
        $departments = Department::all();
        return view('users.edit', compact('user', 'departments'));
    }

    // Update an existing user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'role' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department_id;
        $user->position = $request->position;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Employee updated successfully.');
    }

    // Delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Employee deleted successfully.');
    }

    // Show dashboard / employee-specific data
    public function show()
    {
        $user = auth()->user();

        if ($user->isCEO()) {
            // CEO dashboard data
            $departmentsCount = Department::count();
            $usersCount = User::count();
            $tasksCount = Task::count();
            $leaveRequestsCount = LeaveRequest::count();

            return view('dashboard_ceo', compact(
                'departmentsCount', 'usersCount', 'tasksCount', 'leaveRequestsCount'
            ));
        } else {
            // Employee dashboard data
            $tasks = $user->assignedTasks()->latest()->take(5)->get();
            $notifications = $user->notifications()->latest()->take(5)->get();

            return view('dashboard_employee', compact('tasks', 'notifications'));
        }
    }
}
