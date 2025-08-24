@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-store me-2"></i>Vendor Details
        </h2>
        <div>
            <a href="{{ route('shop.vendors.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Vendors
            </a>
            <a href="{{ route('shop.vendors.edit', $vendor) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-1"></i> Edit Vendor
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Vendor Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-store fa-2x text-primary"></i>
                        </div>
                        <h4 class="mt-3 mb-0 fw-bold">{{ $vendor->name }}</h4>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex">
                            <div class="me-3">
                                <span class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-muted small">Location</div>
                                <div class="fw-medium">{{ $vendor->location }}</div>
                            </div>
                        </div>
                        
                        <div class="list-group-item px-0 py-3 d-flex">
                            <div class="me-3">
                                <span class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-phone text-success"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-muted small">Phone Number</div>
                                <div class="fw-medium">{{ $vendor->phone }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-box me-2"></i>Products
                    </h5>
                    <span class="badge bg-primary">{{ $vendor->products->count() }} products</span>
                </div>
                <div class="card-body p-0">
                    @if($vendor->products->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Product Name</th>
                                        <th class="text-end pe-3">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendor->products as $product)
                                        <tr>
                                            <td class="ps-3 fw-medium">{{ $product->name }}</td>
                                            <td class="text-end pe-3">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-tag me-1"></i> ${{ $product->price }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-box-open fa-3x text-muted"></i>
                            </div>
                            <p class="text-muted mb-3">No products found for this vendor</p>
                            <a href="{{ route('shop.products.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus-circle me-1"></i> Add Product
                            </a>
                        </div>
                    @endif
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
.table th, .table td {
    padding: 0.75rem 1rem;
}
.badge.bg-success {
    font-weight: 500;
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
}
</style>
@endsection