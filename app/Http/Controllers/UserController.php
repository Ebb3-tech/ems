<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserCreatedOrUpdated;

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

    // Store a new user and send email notification
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

        // Keep plain password for email
        $plainPassword = $request->password;

        $userData = $request->only('name', 'email', 'department_id', 'position', 'role');
        $userData['password'] = Hash::make($plainPassword);

        $user = User::create($userData);

        // Send email notification
        $user->notify(new UserCreatedOrUpdated('created', $plainPassword));

        return redirect()->route('users.index')
            ->with('success', 'Employee created successfully and notified via email.');
    }

    // Show form to edit an existing user
    public function edit(User $user)
    {
        $departments = Department::all();
        return view('users.edit', compact('user', 'departments'));
    }

    // Update an existing user and send email notification
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

        $passwordChanged = false;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $passwordChanged = true;
        }

        $user->save();

        // Send email notification
        $user->notify(new UserCreatedOrUpdated('updated', $passwordChanged ? $request->password : null));

        return redirect()->route('users.index')
            ->with('success', 'Employee updated successfully and notified via email.');
    }

    // Delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Employee deleted successfully.');
    }
}
