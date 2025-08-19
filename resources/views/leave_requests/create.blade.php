@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Submit Leave Request</h1>

    <form action="{{ route('leave-requests.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" id="end_date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea id="reason" name="reason" class="form-control" rows="4" required>{{ old('reason') }}</textarea>
            @error('reason')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
        <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
