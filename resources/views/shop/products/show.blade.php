@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-box me-2"></i>Product Details
        </h2>
        <div>
            <a href="{{ route('shop.products.edit', $product->id) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i> Edit Product
            </a>
            <a href="{{ route('shop.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-box-open fa-2x text-primary"></i>
                        </div>
                        <h4 class="mt-3 mb-0 fw-bold">{{ $product->name }}</h4>
                        <div class="mt-2">
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-tag me-1"></i> ${{ $product->price ?? '0.00' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex">
                            <div class="me-3">
                                <span class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-align-left text-secondary"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-muted small">Description</div>
                                <div class="fw-medium">{{ $product->description ?? 'No description available' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-store me-2"></i>Vendor Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($product->vendor)
                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-store fa-2x text-primary"></i>
                            </div>
                            <h5 class="mt-3 mb-0 fw-bold">{{ $product->vendor->name }}</h5>
                        </div>
                        
                        <div class="list-group list-group-flush">
                            @if($product->vendor->location)
                            <div class="list-group-item px-0 py-2 d-flex">
                                <div class="me-2">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Location</div>
                                    <div>{{ $product->vendor->location }}</div>
                                </div>
                            </div>
                            @endif
                            
                            @if($product->vendor->phone)
                            <div class="list-group-item px-0 py-2 d-flex">
                                <div class="me-2">
                                    <i class="fas fa-phone text-success"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Phone</div>
                                    <div>{{ $product->vendor->phone }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('shop.vendors.show', $product->vendor->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> View Vendor Details
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-store-slash fa-3x text-muted"></i>
                            </div>
                            <p class="text-muted mb-3">No vendor associated with this product</p>
                            <a href="{{ route('shop.vendors.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Add Vendor
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-cog me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('shop.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Product
                        </a>
                        <form action="{{ route('shop.products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="fas fa-trash me-1"></i> Delete Product
                            </button>
                        </form>
                    </div>
                </div>
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
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.list-group-item {
    border-left: 0;
    border-right: 0;
    border-color: #f5f5f5;
}
.badge.bg-success {
    font-weight: 500;
    font-size: 0.9rem;
}
</style>
@endsection