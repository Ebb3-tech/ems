@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-store-alt me-2 text-primary"></i>Add Vendor
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('shop.vendors.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">Vendor Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-building text-muted"></i>
                                </span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name') }}" 
                                    placeholder="Enter vendor name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label fw-medium">Location</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                    id="location" name="location" value="{{ old('location') }}" 
                                    placeholder="Enter vendor location" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-medium">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-phone text-muted"></i>
                                </span>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" name="phone" value="{{ old('phone') }}" 
                                    placeholder="Enter phone number" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('shop.vendors.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Save Vendor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Font Awesome if not already included in your layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.input-group-text {
    border-right: 0;
}
.form-control {
    border-left: 0;
}
.input-group:focus-within .input-group-text {
    border-color: #86b7fe;
}
.card {
    border-radius: 8px;
    overflow: hidden;
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