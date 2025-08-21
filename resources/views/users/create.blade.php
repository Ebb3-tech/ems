@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>{{ isset($user) ? 'Edit Employee' : 'Add Employee' }}</h1>

    <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required>
        </div>

        {{-- Department --}}
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-control">
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id', $user->department_id ?? '') == $department->id)>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Position --}}
        <div class="mb-3">
            <label for="position" class="form-label">Position</label>
            <input type="text" name="position" id="position" value="{{ old('position', $user->position ?? '') }}" class="form-control">
        </div>

        {{-- Role --}}
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="1" {{ old('role', $user->role ?? '') == 1 ? 'selected' : '' }}>Employee</option>
                <option value="2" {{ old('role', $user->role ?? '') == 2 ? 'selected' : '' }}>Call Center</option>
                <option value="3" {{ old('role', $user->role ?? '') == 3 ? 'selected' : '' }}>Marketing</option>
                <option value="4" {{ old('role', $user->role ?? '') == 4 ? 'selected' : '' }}>Shop</option>
                <option value="5" {{ old('role', $user->role ?? '') == 5 ? 'selected' : '' }}>Manager</option>
            </select>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">{{ isset($user) ? 'New Password (leave blank to keep current)' : 'Password' }}</label>
            <input type="password" name="password" id="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ isset($user) ? 'Update Employee' : 'Add Employee' }}
        </button>
    </form>
</div>
@endsection
