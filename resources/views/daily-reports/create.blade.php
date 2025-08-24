@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-file-alt me-2"></i>Create Daily Report
        </h2>
        <div>
            <a href="{{ route('daily-reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Reports
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Report Information</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('daily-reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="report_date" class="form-label fw-medium">Report Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </span>
                        <input 
                            type="date" 
                            id="report_date" 
                            name="report_date" 
                            value="{{ old('report_date', date('Y-m-d')) }}" 
                            class="form-control @error('report_date') is-invalid @enderror"
                            required
                        >
                        @error('report_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-medium">Content <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-pen-alt text-primary"></i>
                        </span>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="5" 
                            class="form-control @error('content') is-invalid @enderror"
                            placeholder="Describe your work activities for today..."
                            required
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">
                        <small>Include details about tasks completed, challenges faced, and planned activities</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label fw-medium">Upload Image</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-image text-primary"></i>
                        </span>
                        <input 
                            type="file" 
                            id="image" 
                            name="image" 
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/*"
                        >
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">
                        <small>Optional: Upload an image related to your work (max 2MB)</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('daily-reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-paper-plane me-1"></i> Submit Report
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