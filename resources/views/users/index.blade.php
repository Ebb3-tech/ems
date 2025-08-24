@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-users me-2"></i>Employees</h2>
        <div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Add Employee
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
            <h5 class="mb-0 fw-bold">Employee List</h5>
            <span class="badge bg-primary">{{ count($users) }} employees</span>
        </div>
        <div class="card-body p-0">
            @if(count($users) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Role</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="ps-3 fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-1"></i> {{ $user->email }}
                                    </td>
                                    <td>
                                        @if($user->department)
                                            <i class="fas fa-building text-muted me-1"></i> {{ $user->department->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->position)
                                            <i class="fas fa-briefcase text-muted me-1"></i> {{ $user->position }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $roleClass = [
                                                'admin' => 'danger',
                                                'manager' => 'primary',
                                                'supervisor' => 'warning',
                                                'employee' => 'success',
                                                'intern' => 'info'
                                            ][$user->role] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $roleClass }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit employee">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this employee?')"
                                                   title="Delete employee">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-user-slash fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted mb-3">No employees found</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i> Add Your First Employee
                    </a>
                </div>
            @endif
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
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.2s;
    border: none;
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