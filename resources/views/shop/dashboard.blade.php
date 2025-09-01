@extends('layouts.app')

@section('content')
<div class="container mt-3 d-flex flex-column min-vh-100">
    <!-- Header Section - Responsive on all devices -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <h2 class="fw-bold text-primary fs-4 fs-md-3">Shop Dashboard</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('shop.vendors.create') }}" class="btn btn-sm btn-primary me-1 mb-1 mb-md-0">
                <i class="fas fa-store me-1"></i> Add Vendor
            </a>
            <a href="{{ route('shop.products.create') }}" class="btn btn-sm btn-success me-1 mb-1 mb-md-0">
                <i class="fas fa-box me-1"></i> Add Product
            </a>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary me-1 mb-1 mb-md-0">
                <i class="fas fa-cash-register me-1"></i> Sales
            </a>
            <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-warning me-1 mb-1 mb-md-0">
                <i class="fas fa-clock me-1"></i> Attendance
            </a>
            <a href="{{ route('leave-requests.index') }}" class="btn btn-sm btn-info text-white">
    <i class="fas fa-calendar-alt me-1"></i> Leave Requests
</a>

            <a href="{{ route('daily-reports.index') }}" class="btn btn-sm btn-info text-white">
                <i class="fas fa-file-alt me-1"></i> Report
            </a>
        </div>
    </div>

    {{-- Dashboard Cards - Responsive Grid --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm h-100">
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
                    <a href="{{ route('shop.vendors.index') }}" class="text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="fas fa-eye me-1"></i> View Vendors
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm h-100">
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
                    <a href="{{ route('shop.products.index') }}" class="text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="fas fa-eye me-1"></i> View Products
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm h-100">
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
                    <a href="{{ route('shop.walk-in-customers.index') }}" class="text-decoration-none d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="fas fa-eye me-1"></i> View Customers
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('tasks.index') }}" class="text-decoration-none">
                <div class="card border-0 rounded-3 shadow-sm h-100">
                    <div class="card-body bg-dark text-white rounded-3 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">My Tasks</h6>
                                <h3 class="mb-0 fw-bold">{{ Auth::user()->assignedTasks()->count() }}</h3>
                            </div>
                            <i class="fas fa-tasks fa-2x opacity-50"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-2">
                        <span class="d-flex align-items-center justify-content-center justify-content-md-start">
                            <i class="fas fa-clipboard-check me-1"></i> Manage Tasks
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    


    {{-- Latest Vendors Table - Mobile Responsive --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fs-6 fs-md-5"><i class="fas fa-store me-1"></i> Recent Vendors</h5>
            <a href="{{ route('shop.vendors.index') }}" class="btn btn-sm btn-outline-primary d-md-none">
                View All
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th class="d-none d-md-table-cell">Phone</th>
                            <th class="d-none d-md-table-cell">Location</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Vendor::latest()->take(5)->get() as $vendor)
                        <tr>
                            <td class="ps-3 fw-medium">
                                {{ $vendor->name }}
                                <div class="small text-muted d-md-none">
                                    {{ $vendor->phone }} • {{ $vendor->location }}
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell"><i class="fas fa-phone-alt text-muted me-1"></i> {{ $vendor->phone }}</td>
                            <td class="d-none d-md-table-cell"><i class="fas fa-map-marker-alt text-muted me-1"></i> {{ $vendor->location }}</td>
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
            <div class="d-md-none card-footer bg-white py-2 text-center">
                <a href="{{ route('shop.vendors.index') }}" class="text-decoration-none">View All Vendors</a>
            </div>
        </div>
    </div>

    {{-- Latest Products Table - Mobile Responsive --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fs-6 fs-md-5"><i class="fas fa-box me-1"></i> Recent Products</h5>
            <a href="{{ route('shop.products.index') }}" class="btn btn-sm btn-outline-success d-md-none">
                View All
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th class="d-none d-md-table-cell">Vendor</th>
                            <th class="d-none d-md-table-cell">Price</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Product::with('vendor')->latest()->take(5)->get() as $product)
                        <tr>
                            <td class="ps-3 fw-medium">
                                {{ $product->name }}
                                <div class="small text-muted d-md-none">
                                    {{ $product->vendor->name ?? '-' }} • ${{ $product->price }}
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell"><i class="fas fa-store text-muted me-1"></i> {{ $product->vendor->name ?? '-' }}</td>
                            <td class="d-none d-md-table-cell"><i class="fas fa-tag text-muted me-1"></i> ${{ $product->price }}</td>
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
            <div class="d-md-none card-footer bg-white py-2 text-center">
                <a href="{{ route('shop.products.index') }}" class="text-decoration-none">View All Products</a>
            </div>
        </div>
    </div>
    
    {{-- Copyright Footer --}}
    <div class="mt-auto py-3 text-center border-top">
        <p class="text-muted mb-0 small">
            <i class="far fa-copyright me-1"></i>2025 Designed by Ebenezer. All Rights Reserved.
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
.clickable-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.clickable-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.1) !important;
}
.table th, .table td {
    padding: 0.6rem 0.75rem;
}
.btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-warning:hover, 
.btn-outline-secondary:hover, .btn-outline-info:hover, .btn-outline-dark:hover {
    color: white;
}

/* Mobile optimizations */
@media (max-width: 767.98px) {
    .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
    .table th, .table td {
        padding: 0.5rem 0.5rem;
    }
    .card-body {
        padding: 0.75rem;
    }
    .card-footer {
        padding: 0.5rem 0.75rem;
    }
    .fs-6 {
        font-size: 0.95rem !important;
    }
    .table-responsive {
        scrollbar-width: none; /* Firefox */
    }
    .table-responsive::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }
}

/* Very small devices */
@media (max-width: 575.98px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    .btn {
        padding: 0.25rem 0.4rem;
        font-size: 0.8rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status card click handlers
    document.querySelectorAll('.clickable-card').forEach(card => {
        card.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            window.location.href = `/tasks?status=${status}`;
        });
    });
    
    // Add animations and hover effects
    const cards = document.querySelectorAll('.card:not(.clickable-card)');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection