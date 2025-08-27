@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-tasks me-2"></i>My Tasks</h2>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item {{ request('status') == '' ? 'active' : '' }}" href="{{ route('tasks.index') }}">All Tasks</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item {{ request('status') == 'not_started' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'not_started']) }}">Not Started</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'in_progress' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'in_progress']) }}">In Progress</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'completed']) }}">Completed</a></li>
                    <li><a class="dropdown-item {{ request('status') == 'on_hold' ? 'active' : '' }}" href="{{ route('tasks.index', ['status' => 'on_hold']) }}">On Hold</a></li>
                </ul>
            </div>
            @if(Auth::user()->role == 5)
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Task
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Task Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Tasks</h6>
                            <h4 class="mb-0 fw-bold">{{ $tasks->where('assigned_to', Auth::id())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-spinner text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">In Progress</h6>
                            <h4 class="mb-0 fw-bold">{{ $tasks->where('assigned_to', Auth::id())->where('status', 'in_progress')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Completed</h6>
                            <h4 class="mb-0 fw-bold">{{ $tasks->where('assigned_to', Auth::id())->where('status', 'completed')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Overdue</h6>
                            <h4 class="mb-0 fw-bold">{{ $tasks->where('assigned_to', Auth::id())->where('status', '!=', 'completed')->filter(function($task) { return $task->deadline && \Carbon\Carbon::parse($task->deadline)->isPast(); })->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">My Task List</h5>
            <span class="badge bg-primary">{{ $tasks->where('assigned_to', Auth::id())->count() }} tasks</span>
        </div>
        <div class="card-body p-0">
            @if($tasks->where('assigned_to', Auth::id())->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Title</th>
                                <th>Assigned By</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks->where('assigned_to', Auth::id())->sortByDesc('created_at') as $task)
                                <tr>
                                    <td class="ps-3 fw-medium">
                                        <i class="fas fa-clipboard-list text-primary me-1"></i> {{ $task->title }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <span>{{ $task->assignedBy?->name }}</span>
                                        </div>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            @php
                                                $statusClass = [
                                                    'not_started' => 'secondary',
                                                    'in_progress' => 'warning',
                                                    'completed' => 'success',
                                                    'on_hold' => 'info',
                                                    'cancelled' => 'danger'
                                                ][$task->status] ?? 'secondary';
                                                
                                                $statusText = ucfirst(str_replace('_', ' ', $task->status));
                                            @endphp
                                            
                                            <button class="btn btn-sm badge bg-{{ $statusClass }} dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ $statusText }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><h6 class="dropdown-header">Update Status</h6></li>
                                                <li>
                                                    <form action="{{ route('callcenter.tasks.update-status', $task->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="not_started">
                                                        <button type="submit" class="dropdown-item {{ $task->status == 'not_started' ? 'active' : '' }}">Not Started</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('callcenter.tasks.update-status', $task->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="dropdown-item {{ $task->status == 'in_progress' ? 'active' : '' }}">In Progress</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('callcenter.tasks.update-status', $task->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="dropdown-item {{ $task->status == 'completed' ? 'active' : '' }}">Completed</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('callcenter.tasks.update-status', $task->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="on_hold">
                                                        <button type="submit" class="dropdown-item {{ $task->status == 'on_hold' ? 'active' : '' }}">On Hold</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        @if($task->deadline)
                                            <div class="d-flex align-items-center">
                                                <i class="far fa-calendar-alt text-muted me-1"></i> 
                                                {{ $task->deadline->format('Y-m-d') }}
                                                <div class="small text-muted ms-1">{{ $task->deadline->format('H:i') }}</div>
                                            </div>
                                            
                                            @if($task->status != 'completed')
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $deadline = \Carbon\Carbon::parse($task->deadline);
                                                    $isPast = $now->gt($deadline);
                                                    
                                                    if ($isPast) {
                                                        $diff = $now->diff($deadline);
                                                    } else {
                                                        $diff = $deadline->diff($now);
                                                    }
                                                    
                                                    $timeLeft = [];
                                                    if ($diff->d > 0) $timeLeft[] = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
                                                    if ($diff->h > 0) $timeLeft[] = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
                                                    if ($diff->i > 0) $timeLeft[] = $diff->i . ' min' . ($diff->i > 1 ? 's' : '');
                                                    
                                                    $timeLeftText = implode(', ', $timeLeft);
                                                    if (empty($timeLeft)) $timeLeftText = 'less than a minute';
                                                @endphp
                                                
                                                @if($isPast)
                                                    <small class="text-danger">
                                                        <i class="fas fa-exclamation-circle me-1"></i> Overdue by {{ $timeLeftText }}
                                                    </small>
                                                @else
                                                    <small class="{{ $diff->d <= 2 ? 'text-warning' : 'text-muted' }}">
                                                        <i class="fas fa-hourglass-half me-1"></i> {{ $timeLeftText }} left
                                                    </small>
                                                @endif
                                            @else
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i> Completed
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('tasks.show', $task) }}"><i class="fas fa-eye me-2 text-info"></i> View Details</a></li>
                                                <li><a class="dropdown-item" href="{{ route('tasks.edit', $task) }}"><i class="fas fa-edit me-2 text-warning"></i> Edit Task</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                @if(Auth::user()->role == 5)
                                                <li>
                                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                              onclick="return confirm('Are you sure you want to delete this task?')">
                                                            <i class="fas fa-trash me-2"></i> Delete Task
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-check fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted mb-3">You don't have any assigned tasks yet</p>
                    @if(Auth::user()->role == 5)
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Create New Task
                    </a>
                    @endif
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
.rounded-circle {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dropdown-toggle::after {
    margin-left: 0.5em;
}
.dropdown-item.active, .dropdown-item:active {
    background-color: #f8f9fa;
    color: #212529;
}
.dropdown-item:hover {
    background-color: rgba(0,0,0,0.05);
}
.dropdown-item {
    padding: 0.5rem 1rem;
}
</style>
@endsection