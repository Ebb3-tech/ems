@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-{{ isset($department) ? 'edit' : 'plus-circle' }} me-2"></i>
            {{ isset($department) ? 'Edit Department' : 'Add Department' }}
        </h2>
        <div>
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Departments
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Department Information</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ isset($department) ? route('departments.update', $department) : route('departments.store') }}" method="POST">
                @csrf
                @if(isset($department)) @method('PUT') @endif

                <div class="mb-3">
                    <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-building text-primary"></i>
                        </span>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $department->name ?? '') }}" 
                            class="form-control @error('name') is-invalid @enderror" 
                            placeholder="Enter department name"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-medium">Description</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-align-left text-primary"></i>
                        </span>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4" 
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter department description (optional)"
                        >{{ old('description', $department->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-{{ isset($department) ? 'save' : 'plus-circle' }} me-1"></i> 
                        {{ isset($department) ? 'Update Department' : 'Save Department' }}
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
.form-control {
    border-left: 0;
}
.input-group:focus-within .input-group-text {
    border-color: #86b7fe;
}
.form-control:focus {
    box-shadow: none;
}
.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-radius: 0.375rem;
}
</style>
@endsection