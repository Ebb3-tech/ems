@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm p-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-light rounded-circle p-3 me-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div>
                        <h2 class="mb-1 text-dark fw-bold">Welcome back, {{ auth()->user()->name }}</h2>
                        <p class="text-muted mb-0">Here's what's happening with your work today</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-8 mb-4">
            {{-- Profile Info Card --}}
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <path d="M20 8v6"></path>
                                <path d="M23 11h-6"></path>
                            </svg>
                        </div>
                        <h5 class="mb-0 fw-semibold text-dark">Your Profile</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-muted me-2" style="min-width: 60px;">
                                    <small class="fw-medium">Name</small>
                                </div>
                                <div class="text-dark fw-medium">{{ auth()->user()->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-muted me-2" style="min-width: 60px;">
                                    <small class="fw-medium">Email</small>
                                </div>
                                <div class="text-dark">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-muted me-2" style="min-width: 80px;">
                                    <small class="fw-medium">Department</small>
                                </div>
                                <div class="text-dark">{{ auth()->user()->department->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-muted me-2" style="min-width: 60px;">
                                    <small class="fw-medium">Position</small>
                                </div>
                                <div class="text-dark">{{ auth()->user()->position ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-muted me-2" style="min-width: 50px;">
                                    <small class="fw-medium">Phone</small>
                                </div>
                                <div class="text-dark">{{ auth()->user()->phone ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="text-muted me-2" style="min-width: 60px;">
                                    <small class="fw-medium">Address</small>
                                </div>
                                <div class="text-dark">{{ auth()->user()->address ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tasks Card --}}
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-2 p-2 me-2">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14,2 14,8 20,8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10,9 9,9 8,9"></polyline>
                                </svg>
                            </div>
                            <h5 class="mb-0 fw-semibold text-dark">Your Tasks</h5>
                        </div>
                        @if($tasks->count())
                            <span class="badge bg-primary rounded-pill">{{ $tasks->count() }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($tasks->count())
                        <div class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <div class="list-group-item border-0 px-0 py-3 bg-white">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 mt-1">
                                            @if($task->status == 'completed')
                                                <div class="bg-success bg-opacity-20 rounded-circle p-1">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success">
                                                        <polyline points="20,6 9,17 4,12"></polyline>
                                                    </svg>
                                                </div>
                                            @elseif($task->status == 'in_progress')
                                                <div class="bg-warning bg-opacity-20 rounded-circle p-1">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-warning">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="12,6 12,12 16,14"></polyline>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="bg-secondary bg-opacity-20 rounded-circle p-1">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-secondary">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0 fw-semibold text-dark">{{ $task->title }}</h6>
                                                @if($task->priority)
                                                    <span class="badge 
                                                        @if($task->priority == 'high') bg-danger 
                                                        @elseif($task->priority == 'medium') bg-warning 
                                                        @else bg-info @endif 
                                                        bg-opacity-10 text-dark border-0 ms-2">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mb-2 text-muted small">{{ Str::limit($task->description, 80) }}</p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <span class="badge 
                                                        @if($task->status == 'completed') bg-success 
                                                        @elseif($task->status == 'in_progress') bg-warning 
                                                        @else bg-secondary @endif 
                                                        bg-opacity-10 text-dark border-0">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                    
                                                    @if($task->deadline)
                                                        <small class="text-muted d-flex align-items-center">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <polyline points="12,6 12,12 16,14"></polyline>
                                                            </svg>
                                                            {{ $task->deadline->format('M d, Y') }}
                                                        </small>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-primary px-3 py-1">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                        View
                                                    </a>
                                                    
                                                    @if($task->status != 'completed')
                                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-success px-3 py-1">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                            Update
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($task->updates && $task->updates->count() > 0)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                        </svg>
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
                            <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14,2 14,8 20,8"></polyline>
                                </svg>
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
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-2 p-2 me-2">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-info">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                            </div>
                            <h5 class="mb-0 fw-semibold text-dark">Notifications</h5>
                        </div>
                        @if($notifications->count())
                            <span class="badge bg-danger rounded-pill">{{ $notifications->count() }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count())
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item border-0 px-0 py-3 bg-white">
                                    <p class="mb-2 text-dark">{{ $notification->message }}</p>
                                    <small class="text-muted fw-medium">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-0 opacity-25">
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                            </div>
                            <p class="text-muted mb-0 small">No new notifications.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-2 p-2 me-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-success">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                        </div>
                        <h5 class="mb-0 fw-semibold text-dark">Quick Actions</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-start py-3 border-2 text-start">
                            <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-3">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                    <line x1="8" y1="14" x2="16" y2="14"></line>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-semibold">Request Leave</div>
                                <small class="text-muted">Submit a new leave request</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('attendance.create') }}" class="btn btn-outline-success d-flex align-items-center justify-content-start py-3 border-2 text-start">
                            <div class="bg-success bg-opacity-10 rounded-2 p-2 me-3">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-success">
                                    <path d="M9 11l3 3l8-8"></path>
                                    <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9s4.03-9 9-9c1.51 0 2.93.37 4.18 1.03"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-semibold">Mark Attendance</div>
                                <small class="text-muted">Record your attendance</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('daily-reports.create') }}" class="btn btn-outline-info d-flex align-items-center justify-content-start py-3 border-2 text-start">
                            <div class="bg-info bg-opacity-10 rounded-2 p-2 me-3">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-info">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14,2 14,8 20,8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10,9 9,9 8,9"></polyline>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-semibold">Daily Report</div>
                                <small class="text-muted">Add your daily work report</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection