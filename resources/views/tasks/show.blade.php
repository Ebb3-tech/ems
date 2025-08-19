@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Task Details</h1>

    <div class="card mb-3">
        <div class="card-header">
            <h3>{{ $task->title }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $task->description ?? 'N/A' }}</p>
            <p><strong>Assigned By:</strong> {{ $task->assignedBy?->name ?? 'N/A' }}</p>
            <p><strong>Assigned To:</strong> {{ $task->assignedTo?->name ?? 'N/A' }}</p>
            <p><strong>Department:</strong> {{ $task->department?->name ?? 'N/A' }}</p>
            <p><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>
            <p><strong>Deadline:</strong> {{ $task->deadline ? $task->deadline->format('Y-m-d H:i') : 'N/A' }}</p>

            @if($task->attachment)
                <p>
                    <strong>Attachment:</strong>
                    <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank">View Attachment</a>
                </p>
            @endif
        </div>
    </div>

    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Tasks</a>
    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">Update Task</a>
</div>
@endsection
