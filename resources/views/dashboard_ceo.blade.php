@extends('layouts.app')

@section('content')
<div class="container-fluid container-md mt-3 d-flex flex-column min-vh-100">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
        <h2 class="fw-bold text-primary mb-2 mb-md-0">CEO Dashboard</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('daily-reports.index') }}" class="btn btn-sm btn-info text-white">
                <i class="fas fa-file-alt me-1"></i> Reports
            </a>
            <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-warning">
                <i class="fas fa-clock me-1"></i> Attendance
            </a>
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-bell me-1"></i> Notifications
            </a>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Stats Cards --}}
    <div class="row g-3 mb-4">
        {{-- Departments --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body bg-primary text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 small">Departments</h6>
                            <h3 class="mb-0 fw-bold">{{ $departmentsCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-building fa-2x opacity-50 d-none d-sm-block"></i>
                        <i class="fas fa-building opacity-50 d-sm-none"></i>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('departments.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-cog me-1"></i> <span class="d-none d-sm-inline">Manage</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employees --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body bg-success text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 small">Employees</h6>
                            <h3 class="mb-0 fw-bold">{{ $usersCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50 d-none d-sm-block"></i>
                        <i class="fas fa-users opacity-50 d-sm-none"></i>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-cog me-1"></i> <span class="d-none d-sm-inline">Manage</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tasks --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body bg-warning text-dark rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 small">Tasks</h6>
                            <h3 class="mb-0 fw-bold">{{ $tasksCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-tasks fa-2x opacity-50 d-none d-sm-block"></i>
                        <i class="fas fa-tasks opacity-50 d-sm-none"></i>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-cog me-1"></i> <span class="d-none d-sm-inline">Manage</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leave Requests --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body bg-danger text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 small">Leave Requests</h6>
                            <h3 class="mb-0 fw-bold">{{ $leaveRequestsCount ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-50 d-none d-sm-block"></i>
                        <i class="fas fa-calendar-alt opacity-50 d-sm-none"></i>
                    </div>
                    <div class="mt-2">
                        <a href="" class="btn btn-sm btn-light">
                            <i class="fas fa-cog me-1"></i> <span class="d-none d-sm-inline">Manage</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Customer Requests --}}
        <div class="col-12 col-md-3">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body bg-info text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0 small">Customer Requests</h6>
                            <h3 class="mb-0 fw-bold" id="total-requests">{{ \App\Models\CustomerRequest::count() }}</h3>
                        </div>
                        <i class="fas fa-headset fa-2x opacity-50 d-none d-sm-block"></i>
                        <i class="fas fa-headset opacity-50 d-sm-none"></i>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('callcenter.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-phone-alt me-1"></i> <span class="d-none d-sm-inline">Call Center</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daily Activities --}}
        <div class="col-12 col-md-9">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-header bg-white py-2 border-0">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Daily Activities</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary p-2 me-3 text-white">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Daily Reports</h6>
                                            <p class="mb-0 small text-muted d-none d-sm-block">View reports</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-end">
                                        <a href="{{ route('daily-reports.index') }}" class="btn btn-sm btn-primary">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-warning p-2 me-3 text-white">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Attendance</h6>
                                            <p class="mb-0 small text-muted d-none d-sm-block">Track attendance</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-end">
                                        <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-warning text-white">
                                            Manage
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary p-2 me-3 text-white">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Notifications</h6>
                                            <p class="mb-0 small text-muted d-none d-sm-block">System alerts</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-end">
                                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-secondary">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright Footer - push to bottom with margin-top:auto --}}
    <div class="mt-auto py-3 text-center border-top">
        <p class="text-muted mb-0">
            <i class="far fa-copyright me-1"></i> 2025 Designed by Ebenezer. All Rights Reserved.
        </p>
    </div>
</div>

{{-- Add Font Awesome if not already included in your layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.card {
    overflow: hidden;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-3px);
}
.rounded-circle {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 576px) {
    .rounded-circle {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
    h3 {
        font-size: 1.5rem;
    }
    h6 {
        font-size: 0.9rem;
    }
}
</style>
@endsection