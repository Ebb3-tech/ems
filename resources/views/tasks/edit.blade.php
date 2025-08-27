@extends('layouts.app')

@section('content')
@php
    $authUser = auth()->user();
@endphp

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-{{ isset($task) ? 'edit' : 'plus-circle' }} me-2"></i>
            {{ isset($task) ? 'Update Task' : 'Add Task' }}
        </h2>
        <div>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Tasks
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Task Information</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}" 
                          method="POST" enctype="multipart/form-data" 
                          onsubmit="this.querySelector('button[type=submit]').disabled = true;"
                          novalidate>
                        @csrf
                        @if(isset($task)) @method('PUT') @endif

                        @if($authUser->role == 5) {{-- Only role 5 (admin/CEO) can edit all fields --}}
                            <div class="mb-3">
                                <label for="title" class="form-label fw-medium">Title <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-clipboard-list text-primary"></i>
                                    </span>
                                    <input type="text" id="title" name="title" 
                                        value="{{ old('title', $task->title ?? '') }}" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        placeholder="Enter task title" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-medium">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-align-left text-primary"></i>
                                    </span>
                                    <textarea id="description" name="description" rows="4" 
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Enter detailed description of the task">{{ old('description', $task->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="assigned_by" class="form-label fw-medium">Assigned By <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-user-tie text-primary"></i>
                                        </span>
                                        <select id="assigned_by" name="assigned_by" 
                                            class="form-select @error('assigned_by') is-invalid @enderror" required>
                                            <option value="">Select assigner</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" @selected(old('assigned_by', $task->assigned_by ?? '') == $user->id)>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assigned_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="assigned_to" class="form-label fw-medium">Assigned To</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-user-check text-primary"></i>
                                        </span>
                                        <select id="assigned_to" name="assigned_to" 
                                            class="form-select @error('assigned_to') is-invalid @enderror">
                                            <option value="">Select assignee</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to ?? '') == $user->id)>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="department_id" class="form-label fw-medium">Department</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-building text-primary"></i>
                                        </span>
                                        <select id="department_id" name="department_id" 
                                            class="form-select @error('department_id') is-invalid @enderror">
                                            <option value="">Select department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" @selected(old('department_id', $task->department_id ?? '') == $department->id)>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="priority" class="form-label fw-medium">Priority</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-flag text-primary"></i>
                                        </span>
                                        <select id="priority" name="priority" 
                                            class="form-select @error('priority') is-invalid @enderror">
                                            <option value="low" @selected(old('priority', $task->priority ?? '') == 'low')>Low</option>
                                            <option value="medium" @selected(old('priority', $task->priority ?? '') == 'medium')>Medium</option>
                                            <option value="high" @selected(old('priority', $task->priority ?? '') == 'high')>High</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deadline" class="form-label fw-medium">Deadline</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="datetime-local" id="deadline" name="deadline" 
                                        value="{{ old('deadline', isset($task->deadline) ? $task->deadline->format('Y-m-d\TH:i') : '') }}" 
                                        class="form-control @error('deadline') is-invalid @enderror">
                                    @error('deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <small>Set the target completion date and time</small>
                                </div>
                            </div>
                        @else
                            {{-- Show task information as read-only for non-admin users --}}
                            <div class="alert alert-info mb-4">
                                <h6 class="mb-2 fw-bold"><i class="fas fa-info-circle me-2"></i>Task Information</h6>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Title:</div>
                                    <div class="col-md-9">{{ $task->title }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Description:</div>
                                    <div class="col-md-9">{{ $task->description ?? 'No description provided' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Assigned By:</div>
                                    <div class="col-md-9">{{ $task->assignedBy->name ?? 'Not specified' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Assigned To:</div>
                                    <div class="col-md-9">{{ $task->assignedTo->name ?? 'Not assigned' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Priority:</div>
                                    <div class="col-md-9">
                                        <span class="badge bg-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'info') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 fw-bold">Deadline:</div>
                                    <div class="col-md-9">{{ $task->deadline ? $task->deadline->format('M d, Y g:i A') : 'No deadline' }}</div>
                                </div>
                            </div>
                        @endif

                        {{-- Status field shown to everyone --}}
                        <div class="mb-3">
                            <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-tasks text-primary"></i>
                                </span>
                                <select id="status" name="status" 
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" @selected(old('status', $task->status ?? '') == 'pending')>Pending</option>
                                    <option value="in_progress" @selected(old('status', $task->status ?? '') == 'in_progress')>In Progress</option>
                                    <option value="completed" @selected(old('status', $task->status ?? '') == 'completed')>Completed</option>
                                    <option value="on_hold" @selected(old('status', $task->status ?? '') == 'on_hold')>On Hold</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Attachment field shown to everyone --}}
                        <div class="mb-4">
                            <label for="attachment" class="form-label fw-medium">Attachment</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-paperclip text-primary"></i>
                                </span>
                                <input type="file" id="attachment" name="attachment" 
                                    class="form-control @error('attachment') is-invalid @enderror">
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            @if(isset($task) && $task->attachment)
                                <div class="mt-2 d-flex align-items-center">
                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                    <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank" class="text-decoration-none">
                                        View Current Attachment
                                    </a>
                                </div>
                            @endif
                            
                            <div class="form-text">
                                <small>Upload relevant documents or images (optional)</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="fas fa-save me-1"></i> {{ isset($task) ? 'Update Task' : 'Save Task' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Help & Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6 class="mb-2 fw-bold"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                        <ul class="mb-0 ps-3">
                            <li>Be clear and specific when creating task titles</li>
                            <li>Set realistic deadlines for task completion</li>
                            <li>Update the task status as work progresses</li>
                        </ul>
                    </div>
                    
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-flag-checkered me-2"></i>Status Guide
                    </h6>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex align-items-center px-0">
                            <span class="badge bg-secondary me-2">Pending</span>
                            <span>Task has not been started yet</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center px-0">
                            <span class="badge bg-warning text-dark me-2">In Progress</span>
                            <span>Task is currently being worked on</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center px-0">
                            <span class="badge bg-success me-2">Completed</span>
                            <span>Task has been finished</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center px-0">
                            <span class="badge bg-info me-2">On Hold</span>
                            <span>Task is temporarily paused</span>
                        </li>
                    </ul>
                    
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-flag me-2"></i>Priority Levels
                    </h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center px-0">
                            <span class="badge bg-info me-2">Low</span>
                            <span>Can be completed when time permits</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center px-0">
                            <span class="badge bg-warning text-dark me-2">Medium</span>
                            <span>Important but not urgent</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center px-0">
                            <span class="badge bg-danger me-2">High</span>
                            <span>Urgent and requires immediate attention</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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
.input-group-text {
    border-right: 0;
}
.form-control, .form-select {
    border-left: 0;
}
.input-group:focus-within .input-group-text {
    border-color: #86b7fe;
}
.form-control:focus, .form-select:focus {
    box-shadow: none;
}
.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-radius: 0.375rem;
}
.list-group-item {
    border-left: 0;
    border-right: 0;
    border-color: #f5f5f5;
}
</style>
@endsection