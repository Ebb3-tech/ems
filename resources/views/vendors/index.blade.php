@extends('layouts.app')
@section('content')

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-store me-2"></i>Vendors</h2>
        <div>
            <a href="{{ route('shop.vendors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Vendor
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
            <h5 class="mb-0">Vendor List</h5>
            <span class="badge bg-primary">{{ count($vendors) }} vendors</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Location</th>
                            <th>Phone</th>
                            <th>Category</th>
                            <th>Email</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr>
                                <td class="ps-3 fw-medium">
                                    <i class="fas fa-store text-primary me-2"></i> {{ $vendor->name }}
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $vendor->location }}
                                </td>
                                <td>
                                    <i class="fas fa-phone text-success me-1"></i> {{ $vendor->phone }}
                                </td>
                                <td>
                                    @if($vendor->category)
                                        <span class="badge bg-info">{{ $vendor->category }}</span>
                                    @else
                                        <span class="text-muted small">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vendor->email)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-envelope text-primary me-2"></i>
                                            <span>{{ $vendor->email }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted small">Not available</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('shop.vendors.show', $vendor) }}" class="btn btn-sm btn-outline-primary me-1" title="View details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('shop.vendors.edit', $vendor) }}" class="btn btn-sm btn-outline-success me-1" title="Edit vendor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('shop.vendors.destroy', $vendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vendor?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete vendor">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-store-slash fa-3x text-muted mb-2"></i>
                                        <p class="text-muted">No vendors found</p>
                                        <a href="{{ route('shop.vendors.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-1"></i> Add your first vendor
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .table th, .table td {
        padding: 0.6rem 0.75rem;
    }
    .btn-outline-primary, .btn-outline-success, .btn-outline-danger {
        border-radius: 50%;
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-danger:hover {
        color: white;
    }
    .card {
        overflow: hidden;
        transition: box-shadow 0.2s;
        border: none;
    }
    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    .badge.bg-info {
        font-weight: 500;
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
    }
</style>
@endsection