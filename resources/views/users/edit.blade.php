@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-user-edit me-2"></i>Edit Employee
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
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
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
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
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
                                id="position" 
                                name="position" 
                                value="{{ old('position', $user->position) }}" 
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
                <div class="mb-4">
                    <label for="role" class="form-label fw-medium">Role <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-user-tag text-primary"></i>
                        </span>
                        <select 
                            id="role" 
                            name="role" 
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Role</option>
                            <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>
                                <i class="fas fa-user"></i> Employee
                            </option>
                            <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>
                                Call Center
                            </option>
                            <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>
                                Marketing
                            </option>
                            <option value="4" {{ old('role', $user->role) == 4 ? 'selected' : '' }}>
                                Shop
                            </option>
                            <option value="5" {{ old('role', $user->role) == 5 ? 'selected' : '' }}>
                                Manager
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-danger">
                <i class="fas fa-shield-alt me-2"></i>Password Management
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-1">Reset Employee Password</h6>
                    <p class="text-muted mb-0">This will allow you to set a new password for this employee</p>
                </div>
                <a href="#" class="btn btn-outline-danger">
                    <i class="fas fa-key me-1"></i> Reset Password
                </a>
            </div>
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