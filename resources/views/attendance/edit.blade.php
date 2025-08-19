@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Edit Attendance</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('attendance.update', $attendance) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="clock_in" class="form-label">Clock In</label>
            <input type="time" id="clock_in" name="clock_in" value="{{ old('clock_in', $attendance->clock_in) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="clock_out" class="form-label">Clock Out</label>
            <input type="time" id="clock_out" name="clock_out" value="{{ old('clock_out', $attendance->clock_out) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes', $attendance->notes) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Attendance</button>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
@endsection
