@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($task) ? 'Edit Task' : 'Add Task' }}</h1>

    <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}" 
  method="POST" enctype="multipart/form-data" 
  onsubmit="this.querySelector('button[type=submit]').disabled = true;"
  novalidate>
        @csrf
        @if(isset($task)) @method('PUT') @endif

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $task->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Assigned By</label>
            <select name="assigned_by" class="form-control" required>
                <option value="">Select assigner</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('assigned_by', $task->assigned_by ?? '') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Assigned To</label>
            <select name="assigned_to" class="form-control">
                <option value="">Select assignee</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to ?? '') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                <option value="">Select department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id', $task->department_id ?? '') == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Priority</label>
            <select name="priority" class="form-control">
                <option value="low" @selected(old('priority', $task->priority ?? '') == 'low')>Low</option>
                <option value="medium" @selected(old('priority', $task->priority ?? '') == 'medium')>Medium</option>
                <option value="high" @selected(old('priority', $task->priority ?? '') == 'high')>High</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="pending" @selected(old('status', $task->status ?? '') == 'pending')>Pending</option>
                <option value="in_progress" @selected(old('status', $task->status ?? '') == 'in_progress')>In Progress</option>
                <option value="completed" @selected(old('status', $task->status ?? '') == 'completed')>Completed</option>
                <option value="on_hold" @selected(old('status', $task->status ?? '') == 'on_hold')>On Hold</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Deadline</label>
            <input type="datetime-local" name="deadline" value="{{ old('deadline', isset($task->deadline) ? $task->deadline->format('Y-m-d\TH:i') : '') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Attachment</label>
            <input type="file" name="attachment" class="form-control">
            @if(isset($task) && $task->attachment)
                <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank">View Current Attachment</a>
            @endif
        </div>

        <button class="btn btn-primary" type="submit">{{ isset($task) ? 'Update' : 'Save' }}</button>
    </form>
</div>
@endsection
