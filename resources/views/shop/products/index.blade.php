@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-box me-2"></i>Products</h2>
        <div>
            <a href="{{ route('shop.vendors.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-store me-1"></i> View Vendors
            </a>
            <a href="{{ route('shop.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Product List</h5>
            <span class="badge bg-primary">{{ count($products) }} products</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" width="5%">#</th>
                            <th width="25%">Product Name</th>
                            <th width="20%">Vendor</th>
                            <th width="15%">Price</th>
                            <th width="35%">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td class="fw-medium">
                                    <i class="fas fa-box text-primary me-2"></i>{{ $product->name }}
                                </td>
                                <td>
                                    @if($product->vendor)
                                        <i class="fas fa-store text-muted me-1"></i> {{ $product->vendor->name }}
                                    @else
                                        <span class="text-muted">No vendor</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-tag me-1"></i> Frw{{ $product->price ?? '0.00' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($product->description ?? 'No description', 50) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-box-open fa-3x text-muted mb-2"></i>
                                        <p class="text-muted">No products found</p>
                                        <a href="{{ route('shop.products.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-1"></i> Add your first product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Font Awesome if not already included in your layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.table th, .table td {
    padding: 0.75rem 1rem;
}
.badge.bg-success {
    font-weight: 500;
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
}
.card {
    overflow: hidden;
    transition: box-shadow 0.2s;
    border-radius: 8px;
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endsection