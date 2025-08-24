@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-edit me-2"></i>Edit Attendance
        </h2>
        <div>
            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Attendance
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Attendance Information</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('attendance.update', $attendance) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="date" class="form-label fw-medium">Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="far fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="date" id="date" name="date" 
                                        value="{{ old('date', $attendance->date->format('Y-m-d')) }}" 
                                        class="form-control @error('date') is-invalid @enderror" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="clock_in" class="form-label fw-medium">Clock In</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-sign-in-alt text-success"></i>
                                    </span>
                                    <input type="time" id="clock_in" name="clock_in" 
                                        value="{{ old('clock_in', $attendance->clock_in) }}" 
                                        class="form-control @error('clock_in') is-invalid @enderror">
                                    @error('clock_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-muted">
                                    <small>Time when the employee started work</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="clock_out" class="form-label fw-medium">Clock Out</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-sign-out-alt text-danger"></i>
                                    </span>
                                    <input type="time" id="clock_out" name="clock_out" 
                                        value="{{ old('clock_out', $attendance->clock_out) }}" 
                                        class="form-control @error('clock_out') is-invalid @enderror">
                                    @error('clock_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-muted">
                                    <small>Time when the employee ended work</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-medium">Status</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user-clock text-primary"></i>
                                </span>
                                <select name="status" id="status" 
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-medium">Notes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-sticky-note text-muted"></i>
                                </span>
                                <textarea name="notes" id="notes" rows="3" 
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Any additional information...">{{ old('notes', $attendance->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Update Attendance
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
                        <i class="fas fa-info-circle me-2"></i>Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="mb-2 fw-bold"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                        <ul class="mb-0 ps-3">
                            <li>Both clock in and clock out times are optional for some statuses</li>
                            <li>For "Absent" status, you don't need to enter times</li>
                            <li>Use notes to explain unusual circumstances</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-check me-2"></i>Status Guide
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center">
                            <span class="badge bg-success me-2">Present</span>
                            <span>Employee attended work as scheduled</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <span class="badge bg-danger me-2">Absent</span>
                            <span>Employee did not attend work</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <span class="badge bg-warning text-dark me-2">Late</span>
                            <span>Employee arrived after scheduled time</span>
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
</style>
@endsection