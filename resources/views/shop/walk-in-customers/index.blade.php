@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-users me-2"></i>Walk-in Customers</h2>
        <div>
            <a href="{{ route('shop.walk-in-customers.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Register New Customer
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
            <h5 class="mb-0">Customer List</h5>
            <span class="badge bg-primary">{{ count($customers) }} customers</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Phone</th>
                            <th>Need</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Added By</th>
                            <th>Registered</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td class="ps-3 fw-medium">
                                    <i class="fas fa-user text-primary me-1"></i> {{ $customer->name }}
                                </td>
                                <td>
                                    @if($customer->phone)
                                        <i class="fas fa-phone text-muted me-1"></i> {{ $customer->phone }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $customer->need }}">
                                        {{ $customer->need ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($customer->status)
                                        @php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'processing' => 'primary',
                                                'completed' => 'success',
                                                'canceled' => 'danger'
                                            ][$customer->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ ucfirst($customer->status) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $customer->comment }}">
                                        {{ $customer->comment ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-user-tie text-muted me-1"></i> {{ $customer->addedBy->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <i class="far fa-calendar-alt text-muted me-1"></i> {{ $customer->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('shop.walk-in-customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit customer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('shop.walk-in-customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this customer?')"
                                                title="Delete customer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-users-slash fa-3x text-muted mb-2"></i>
                                        <p class="text-muted">No walk-in customers found</p>
                                        <a href="{{ route('shop.walk-in-customers.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-user-plus me-1"></i> Register your first customer
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
.btn-outline-warning, .btn-outline-danger {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-outline-warning:hover, .btn-outline-danger:hover {
    color: white;
}
.card {
    overflow: hidden;
    transition: box-shadow 0.2s;
    border-radius: 8px;
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.badge {
    font-weight: 500;
    padding: 0.4em 0.6em;
}
</style>
@endsection