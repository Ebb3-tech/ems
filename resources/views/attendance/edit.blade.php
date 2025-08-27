@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-sign-out-alt me-2"></i>Clock Out
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
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-sign-out-alt me-2"></i>Clock Out</h5>
                </div>
                <div class="card-body p-4">
                    @if($attendance->clock_out)
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Note:</strong> You've already clocked out for this day. Editing will update your existing clock-out time.
                        </div>
                    @else
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Good job!</strong> Please record your clock-out time to complete your workday.
                        </div>
                    @endif
                    
                    <form action="{{ route('attendance.update', $attendance) }}" method="POST" id="clockOutForm">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">Date</h6>
                                        <p class="card-text h5">{{ $attendance->date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">Clock In Time</h6>
                                        <p class="card-text h5">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="clock_out" class="form-label fw-bold">
                                Clock Out Time <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-sign-out-alt text-danger"></i>
                                </span>
                                <input type="time" id="clock_out" name="clock_out" 
                                    value="{{ old('clock_out', $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : $currentTime) }}" 
                                    class="form-control form-control-lg @error('clock_out') is-invalid @enderror" required>
                                @error('clock_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted">
                                <small>Use 24-hour format (e.g., 13:30 for 1:30 PM)</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-medium">Additional Notes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-sticky-note text-muted"></i>
                                </span>
                                <textarea name="notes" id="notes" rows="3" 
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Any additional information about your workday...">{{ old('notes', $attendance->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-danger btn-lg px-4" id="clockOutBtn">
                                <i class="fas fa-sign-out-alt me-1"></i> Clock Out Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Attendance Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary mb-3">
                        <h6 class="mb-2 fw-bold"><i class="fas fa-user-clock me-2"></i>Status Information</h6>
                        <p class="mb-0">
                            <span class="badge bg-{{ 
                                $attendance->status == 'present' ? 'success' : 
                                ($attendance->status == 'late' ? 'warning' : 
                                ($attendance->status == 'leave' ? 'info' : 'danger')) 
                            }} p-2">
                                {{ ucfirst($attendance->status) }}
                            </span>
                            @if($attendance->status == 'present')
                                <span class="ms-2">On time attendance</span>
                            @elseif($attendance->status == 'late')
                                <span class="ms-2">Arrived after scheduled time</span>
                            @elseif($attendance->status == 'leave')
                                <span class="ms-2">Approved time off</span>
                            @else
                                <span class="ms-2">Not present</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <h6 class="mb-2 fw-bold"><i class="fas fa-lightbulb me-2"></i>Working Hours</h6>
                        <div id="hours-calculation" class="text-center p-2 mb-2 border rounded bg-light">
                            <div id="hours-result" class="h3 mb-0 text-primary">--:--</div>
                        </div>
                        <p class="small mb-0">Your working hours will be calculated based on your clock-in and clock-out times.</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Important Notes
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Your clock-in time is fixed and cannot be changed
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Clock-out time must be after your clock-in time
                        </li>
                        <li class="list-group-item border-0 ps-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Working hours are automatically calculated
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clockInTime = "{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}";
    const clockOutInput = document.getElementById('clock_out');
    const hoursResult = document.getElementById('hours-result');
    const clockOutForm = document.getElementById('clockOutForm');
    
    function calculateHours() {
        if (!clockInTime || !clockOutInput.value) {
            hoursResult.textContent = '--:--';
            return;
        }
        
        // Parse times
        const dateStr = "{{ $attendance->date->format('Y-m-d') }}";
        let clockIn = new Date(`${dateStr}T${clockInTime}`);
        let clockOut = new Date(`${dateStr}T${clockOutInput.value}`);
        
        // If clock out is earlier than clock in, assume it's next day
        if (clockOut < clockIn) {
            clockOut.setDate(clockOut.getDate() + 1);
        }
        
        // Calculate difference in milliseconds
        const diffMs = clockOut - clockIn;
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
        const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        
        // Format result
        hoursResult.textContent = `${diffHours}h ${diffMinutes}m`;
        
        // Add color coding
        if (diffHours < 4) {
            hoursResult.className = 'h3 mb-0 text-warning';
        } else if (diffHours >= 8) {
            hoursResult.className = 'h3 mb-0 text-success';
        } else {
            hoursResult.className = 'h3 mb-0 text-primary';
        }
        
        return diffMs;
    }
    
    // Calculate on page load
    calculateHours();
    
    // Add event listeners
    clockOutInput.addEventListener('change', calculateHours);
    clockOutInput.addEventListener('input', calculateHours);
    
    // Validation to ensure clock-out is after clock-in
    clockOutForm.addEventListener('submit', function(e) {
        const diffMs = calculateHours();
        
        if (diffMs <= 0) {
            e.preventDefault();
            alert('Clock-out time must be after your clock-in time');
            clockOutInput.classList.add('is-invalid');
        } 
    });
});
</script>
@endsection