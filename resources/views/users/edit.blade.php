@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Edit Employee</h2>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $user->name) }}" 
                class="form-control @error('name') is-invalid @enderror"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email) }}" 
                class="form-control @error('email') is-invalid @enderror"
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Department --}}
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select 
                id="department_id" 
                name="department_id" 
                class="form-select @error('department_id') is-invalid @enderror"
            >
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" 
                        {{ (old('department_id', $user->department_id) == $department->id) ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Position --}}
        <div class="mb-3">
            <label for="position" class="form-label">Position</label>
            <input 
                type="text" 
                id="position" 
                name="position" 
                value="{{ old('position', $user->position) }}" 
                class="form-control @error('position') is-invalid @enderror"
            >
            @error('position')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Role --}}
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select 
                id="role" 
                name="role" 
                class="form-select @error('role') is-invalid @enderror"
                required
            >
                <option value="">Select Role</option>
                <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Employee</option>
                <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Call center</option>
                <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>Marketing</option>
                <option value="4" {{ old('role', $user->role) == 4 ? 'selected' : '' }}>Shop</option>
                <option value="5" {{ old('role', $user->role) == 5 ? 'selected' : '' }}>Manager</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">Update Employee</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
