@extends('layouts.app')


@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Dashboard</h1>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        {{-- Departments --}}
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Departments</h5>
                    <p class="card-text fs-2">{{ $departmentsCount ?? 0 }}</p>
                    <a href="{{ route('departments.index') }}" class="btn btn-light btn-sm">Manage Departments</a>
                </div>
            </div>
        </div>

        {{-- Employees --}}
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Employees</h5>
                    <p class="card-text fs-2">{{ $usersCount ?? 0 }}</p>
                    <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">Manage Employees</a>
                </div>
            </div>
        </div>

        {{-- Tasks --}}
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Tasks</h5>
                    <p class="card-text fs-2">{{ $tasksCount ?? 0 }}</p>
                    <a href="{{ route('tasks.index') }}" class="btn btn-light btn-sm">Manage Tasks</a>
                </div>
            </div>
        </div>

        {{-- Leave Requests --}}
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title">Leave Requests</h5>
                    <p class="card-text fs-2">{{ $leaveRequestsCount ?? 0 }}</p>
                    <a href="" class="btn btn-light btn-sm">Manage Leaves</a>
                </div>
            </div>
        </div>

        {{-- Customer Requests Card --}}
        <div class="col-md-3">
            <div class="card text-white bg-info h-100">
    <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
        <i class="bi bi-people fs-1 mb-2"></i>
        <h5 class="card-title">Customer Requests</h5>
        <p class="card-text fs-2" id="total-requests">{{ \App\Models\CustomerRequest::count() }}</p>
        <a href="{{ route('callcenter.index') }}" class="btn btn-light btn-sm mt-2">
            Go to Call Center
        </a>
    </div>
</div>

        </div>
    </div>

    {{-- Daily Activities Quick Links --}}
    <div class="mt-5">
        <h3>Daily Activities</h3>
        <div class="list-group">
            <a href="{{ route('daily-reports.index') }}" class="list-group-item list-group-item-action">
                View Daily Reports
            </a>
            <a href="{{ route('attendance.index') }}" class="list-group-item list-group-item-action">
                Manage Attendance
            </a>
            <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action">
                Notifications
            </a>
        </div>
    </div>
</div>
@endsection
