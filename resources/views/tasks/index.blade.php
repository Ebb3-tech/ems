@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-tasks me-2"></i>Tasks</h2>
        <div>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Task
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
            <h5 class="mb-0 fw-bold">Task List</h5>
            <span class="badge bg-primary">{{ count($tasks) }} tasks</span>
        </div>
        <div class="card-body p-0">
            @if(count($tasks) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Title</th>
                                <th>Assigned By</th>
                                <th>Assigned To</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks->sortByDesc('created_at') as $task)
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
                                        @if($task->assignedTo)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                                                    <i class="fas fa-user-check text-success"></i>
                                                </div>
                                                <span>{{ $task->assignedTo->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
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
                                        @php
                                            $statusClass = [
                                                'not_started' => 'secondary',
                                                'in_progress' => 'warning',
                                                'completed' => 'success',
                                                'on_hold' => 'info',
                                                'cancelled' => 'danger'
                                            ][$task->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
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
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit task">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this task?')"
                                                   title="Delete task">
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
                        <i class="fas fa-clipboard-check fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted mb-3">No tasks found</p>
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Create Your First Task
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