@extends('layouts.app')

@section('content')
<h1 class="mb-4">Add Attendance</h1>
<a href="{{ route('callcenter.index') }}" class="btn btn-primary mb-3">Back to Home</a>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('attendance.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" id="date" name="date" value="{{ old('date') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="clock_in" class="form-label">Clock In</label>
        <input type="time" id="clock_in" name="clock_in" value="{{ old('clock_in') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label for="clock_out" class="form-label">Clock Out</label>
        <input type="time" id="clock_out" name="clock_out" value="{{ old('clock_out') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="present" {{ old('status')=='present'?'selected':'' }}>Present</option>
            <option value="absent" {{ old('status')=='absent'?'selected':'' }}>Absent</option>
            <option value="late" {{ old('status')=='late'?'selected':'' }}>Late</option>
            <option value="leave" {{ old('status')=='leave'?'selected':'' }}>Leave</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
    </div>

    <button type="submit" class="btn btn-success">Save Attendance</button>
    <a href="{{ route('attendance.index') }}" class="btn btn-secondary ms-2">Cancel</a>
</form>
@endsection
