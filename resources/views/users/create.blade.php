@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($user) ? 'Edit Employee' : 'Add Employee' }}</h1>

    <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                <option value="">Select department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id', $user->department_id ?? '') == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Position</label>
            <input type="text" name="position" value="{{ old('position', $user->position ?? '') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="admin" @selected(old('role', $user->role ?? '') == 'admin')>Admin</option>
                <option value="ceo" @selected(old('role', $user->role ?? '') == 'ceo')>CEO</option>
                <option value="hr" @selected(old('role', $user->role ?? '') == 'hr')>HR</option>
                <option value="manager" @selected(old('role', $user->role ?? '') == 'manager')>Manager</option>
                <option value="employee" @selected(old('role', $user->role ?? '') == 'employee')>Employee</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">{{ isset($user) ? 'Update' : 'Save' }}</button>
    </form>
</div>
@endsection
