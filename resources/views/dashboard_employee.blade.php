@extends('layouts.app')

@section('content')
<div class="container mt-3">
    {{-- Welcome Header --}}
    <div class="card border-0 rounded-3 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                </div>
                <div>
                    <h2 class="mb-1 fw-bold text-primary">Welcome back, {{ auth()->user()->name }}</h2>
                    <p class="text-muted mb-0">Here's what's happening with your work today</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8 mb-4">
            {{-- Profile Info Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                        <i class="fas fa-id-card text-primary"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Your Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Name</div>
                                    <div class="fw-medium">{{ auth()->user()->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Email</div>
                                    <div class="fw-medium">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Department</div>
                                    <div class="fw-medium">{{ auth()->user()->department->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="fas fa-briefcase text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Position</div>
                                    <div class="fw-medium">{{ auth()->user()->position ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="fas fa-phone text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Phone</div>
                                    <div class="fw-medium">{{ auth()->user()->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Address</div>
                                    <div class="fw-medium">{{ auth()->user()->address ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tasks Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                            <i class="fas fa-tasks text-warning"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Your Tasks</h5>
                    </div>
                    @if($tasks->count())
                        <span class="badge bg-primary">{{ $tasks->count() }}</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($tasks->count())
                        <div class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 mt-1">
                                            @if($task->status == 'completed')
                                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                    <i class="fas fa-check text-success"></i>
                                                </div>
                                            @elseif($task->status == 'in_progress')
                                                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                    <i class="fas fa-spinner text-warning"></i>
                                                </div>
                                            @else
                                                <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                    <i class="fas fa-circle text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0 fw-semibold">{{ $task->title }}</h6>
                                                @if($task->priority)
                                                    @php
                                                        $priorityClass = [
                                                            'high' => 'danger',
                                                            'medium' => 'warning',
                                                            'low' => 'info'
                                                        ][$task->priority] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $priorityClass }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mb-2 text-muted small">{{ Str::limit($task->description, 80) }}</p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-2 align-items-center">
                                                    @php
                                                        $statusClass = [
                                                            'completed' => 'success',
                                                            'in_progress' => 'warning',
                                                            'pending' => 'secondary'
                                                        ][$task->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                    
                                                    @if($task->deadline)
                                                        <small class="text-muted d-flex align-items-center">
                                                            <i class="far fa-calendar-alt me-1"></i>
                                                            {{ $task->deadline->format('M d, Y') }}
                                                        </small>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                    
                                                    @if($task->status != 'completed')
                                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-edit me-1"></i> Update
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if(isset($task->updates) && $task->updates->count() > 0)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-comment-dots me-1"></i>
                                                        {{ $task->updates->count() }} {{ Str::plural('update', $task->updates->count()) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-0 opacity-25">
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-clipboard-list fa-2x text-muted"></i>
                            </div>
                            <p class="text-muted mb-0">You have no assigned tasks at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Notifications Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                            <i class="fas fa-bell text-info"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Notifications</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-bell-slash fa-2x text-muted"></i>
                        </div>
                        <p class="text-muted mb-0 small">No new notifications.</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                            <i class="fas fa-bolt text-success"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Quick Actions</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-start py-3 text-start">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-calendar-plus fa-lg text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Request Leave</div>
                                <small class="text-muted">Submit a new leave request</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('attendance.create') }}" class="btn btn-outline-success d-flex align-items-center justify-content-start py-3 text-start">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-clock fa-lg text-success"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Mark Attendance</div>
                                <small class="text-muted">Record your attendance</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('daily-reports.create') }}" class="btn btn-outline-info d-flex align-items-center justify-content-start py-3 text-start">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-file-alt fa-lg text-info"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Daily Report</div>
                                <small class="text-muted">Add your daily work report</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('callcenter.index') }}" class="btn btn-outline-dark d-flex align-items-center justify-content-start py-3 text-start">
                            <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-tachometer-alt fa-lg text-dark"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Go to Dashboard</div>
                                <small class="text-muted">Access the main dashboard</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Copyright Footer --}}
    <div class="mt-4 mb-3 py-3 text-center border-top">
        <p class="text-muted mb-0">
            <i class="far fa-copyright me-1"></i> Ebenezer 2025. All Rights Reserved.
        </p>
    </div>
</div>

{{-- Add Font Awesome if not already included in your layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.card {
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.2s;
    border: none;
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.list-group-item {
    background-color: transparent;
}
.btn-outline-primary, .btn-outline-success, .btn-outline-info, .btn-outline-dark {
    border-width: 2px;
}
.btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-info:hover, .btn-outline-dark:hover {
    color: white;
}
.badge {
    font-weight: 500;
    padding: 0.4em 0.6em;
}
</style>
@endsection