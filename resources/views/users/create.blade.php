@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-{{ isset($user) ? 'user-edit' : 'user-plus' }} me-2"></i>
            {{ isset($user) ? 'Edit Employee' : 'Add Employee' }}
        </h2>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Employees
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Employee Information</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="row">
                    {{-- Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $user->name ?? '') }}" 
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter employee's full name"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email', $user->email ?? '') }}" 
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter employee's email address"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Department --}}
                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label fw-medium">Department</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-building text-primary"></i>
                            </span>
                            <select 
                                name="department_id" 
                                id="department_id" 
                                class="form-select @error('department_id') is-invalid @enderror"
                            >
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id', $user->department_id ?? '') == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Position --}}
                    <div class="col-md-6 mb-3">
                        <label for="position" class="form-label fw-medium">Position</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-briefcase text-primary"></i>
                            </span>
                            <input 
                                type="text" 
                                name="position" 
                                id="position" 
                                value="{{ old('position', $user->position ?? '') }}" 
                                class="form-control @error('position') is-invalid @enderror"
                                placeholder="Enter employee's position"
                            >
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label for="role" class="form-label fw-medium">Role <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-user-tag text-primary"></i>
                        </span>
                        <select 
                            name="role" 
                            id="role" 
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Role</option>
                            <option value="1" {{ old('role', $user->role ?? '') == 1 ? 'selected' : '' }}>Employee</option>
                            <option value="2" {{ old('role', $user->role ?? '') == 2 ? 'selected' : '' }}>Call Center</option>
                            <option value="3" {{ old('role', $user->role ?? '') == 3 ? 'selected' : '' }}>Marketing</option>
                            <option value="4" {{ old('role', $user->role ?? '') == 4 ? 'selected' : '' }}>Shop</option>
                            <option value="5" {{ old('role', $user->role ?? '') == 5 ? 'selected' : '' }}>Manager</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Password --}}
<div class="col-md-6 mb-3">
    <label for="password" class="form-label fw-medium">
        {{ isset($user) ? 'New Password (leave blank to keep current)' : 'Password' }}
        @if(!isset($user))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="fas fa-lock text-primary"></i>
        </span>
        <input 
            type="password" 
            name="password" 
            id="password" 
            class="form-control @error('password') is-invalid @enderror"
            placeholder="{{ isset($user) ? 'Enter new password' : 'Enter password' }}"
            {{ isset($user) ? '' : 'required' }}
        >
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Confirm Password --}}
<div class="col-md-6 mb-3">
    <label for="password_confirmation" class="form-label fw-medium">
        Confirm Password
        @if(!isset($user))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="fas fa-lock text-primary"></i>
        </span>
        <input 
            type="password" 
            name="password_confirmation" 
            id="password_confirmation" 
            class="form-control"
            placeholder="Confirm password"
            {{ isset($user) ? '' : 'required' }}
        >
    </div>
</div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-{{ isset($user) ? 'save' : 'plus-circle' }} me-1"></i> 
                        {{ isset($user) ? 'Update Employee' : 'Add Employee' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Font Awesome if not already included in your layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.card {
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.2s;
    border: none;
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.input-group-text {
    border-right: 0;
}
.form-control, .form-select {
    border-left: 0;
}
.input-group:focus-within .input-group-text {
    border-color: #86b7fe;
}
.form-control:focus, .form-select:focus {
    box-shadow: none;
}
.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-radius: 0.375rem;
}
</style>
@endsection