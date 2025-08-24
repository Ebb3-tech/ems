@extends('layouts.app')

@section('content')
<div class="container mt-3 d-flex flex-column min-vh-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">Shop Dashboard</h2>
        <div class="d-flex">
            <a href="{{ route('shop.vendors.create') }}" class="btn btn-sm btn-primary me-2">
                <i class="fas fa-store me-1"></i> Add Vendor
            </a>
            <a href="{{ route('shop.products.create') }}" class="btn btn-sm btn-success me-2">
                <i class="fas fa-box me-1"></i> Add Product
            </a>
            <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-warning me-2">
                <i class="fas fa-clock me-1"></i> Attendance
            </a>
            <a href="{{ route('daily-reports.index') }}" class="btn btn-sm btn-info text-white">
                <i class="fas fa-file-alt me-1"></i> Report
            </a>
        </div>
    </div>

    {{-- Dashboard Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body bg-primary text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Vendors</h6>
                            <h3 class="mb-0 fw-bold">{{ $vendorsCount }}</h3>
                        </div>
                        <i class="fas fa-store fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <a href="{{ route('shop.vendors.index') }}" class="text-decoration-none">
                        <i class="fas fa-eye me-1"></i> View Vendors
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body bg-success text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Products</h6>
                            <h3 class="mb-0 fw-bold">{{ $productsCount }}</h3>
                        </div>
                        <i class="fas fa-box fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <a href="{{ route('shop.products.index') }}" class="text-decoration-none">
                        <i class="fas fa-eye me-1"></i> View Products
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body bg-warning text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Walk-in Customers</h6>
                            <h3 class="mb-0 fw-bold">{{ $walkInCustomersCount }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <a href="{{ route('shop.walk-in-customers.index') }}" class="text-decoration-none">
                        <i class="fas fa-eye me-1"></i> View Customers
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2">
            <h5 class="mb-0"><i class="fas fa-bolt me-1"></i> Quick Actions</h5>
        </div>
        <div class="card-body py-2">
            <div class="row g-2">
                <div class="col-md-3">
                    <a href="{{ route('shop.vendors.create') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-store me-1"></i> Add Vendor
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('shop.products.create') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-box me-1"></i> Add Product
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('shop.walk-in-customers.create') }}" class="btn btn-outline-warning w-100">
                        <i class="fas fa-user-plus me-1"></i> Register Customer
                    </a>
                </div>
                <div class="col-md-3">
                    <div class="d-flex h-100">
                        <a href="{{ route('attendance.create') }}" class="btn btn-outline-secondary flex-grow-1 me-1">
                            <i class="fas fa-clock me-1"></i> Attendance
                        </a>
                        <a href="{{ route('daily-reports.index') }}" class="btn btn-outline-info flex-grow-1">
                            <i class="fas fa-file-alt me-1"></i> Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Vendors Table --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-store me-1"></i> Recent Vendors</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Vendor::latest()->take(5)->get() as $vendor)
                        <tr>
                            <td class="ps-3 fw-medium">{{ $vendor->name }}</td>
                            <td><i class="fas fa-phone-alt text-muted me-1"></i> {{ $vendor->phone }}</td>
                            <td><i class="fas fa-map-marker-alt text-muted me-1"></i> {{ $vendor->location }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('shop.vendors.show', $vendor->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="View details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Latest Products Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-box me-1"></i> Recent Products</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Vendor</th>
                            <th>Price</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Product::with('vendor')->latest()->take(5)->get() as $product)
                        <tr>
                            <td class="ps-3 fw-medium">{{ $product->name }}</td>
                            <td><i class="fas fa-store text-muted me-1"></i> {{ $product->vendor->name ?? '-' }}</td>
                            <td><i class="fas fa-tag text-muted me-1"></i> ${{ $product->price }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('shop.products.show', $product->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="View details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Copyright Footer --}}
    <div class="mt-auto py-3 text-center border-top">
        <p class="text-muted mb-0">
            <i class="far fa-copyright me-1"></i>2025Designed by Ebenezer. All Rights Reserved.
        </p>
    </div>
</div>

{{-- Add Font Awesome if not already included in your layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.card {
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.table th, .table td {
    padding: 0.6rem 0.75rem;
}
.btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-warning:hover, 
.btn-outline-secondary:hover, .btn-outline-info:hover {
    color: white;
}
</style>
@endsection