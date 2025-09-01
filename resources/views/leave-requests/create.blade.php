@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Submit Leave Request</h1>

    <form action="{{ route('leave-requests.store') }}" method="POST">
        @csrf

        {{-- Leave Type --}}
        <div class="mb-3">
            <label for="type" class="form-label">Leave Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">-- Select Type --</option>
                <option value="annual" {{ old('type') == 'annual' ? 'selected' : '' }}>Annual</option>
                <option value="sick" {{ old('type') == 'sick' ? 'selected' : '' }}>Sick</option>
                <option value="maternity" {{ old('type') == 'maternity' ? 'selected' : '' }}>Maternity</option>
                <option value="unpaid" {{ old('type') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Start Date --}}
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
            @error('start_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- End Date --}}
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" id="end_date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
            @error('end_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Reason --}}
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
