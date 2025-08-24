@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-calendar-check me-2"></i>Attendance Records</h2>
        <div>
            <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Attendance
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Attendance History</h5>
            <span class="badge bg-primary">{{ $attendances->total() }} records</span>
        </div>
        <div class="card-body p-0">
            @if($attendances->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Made By</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td class="ps-3">
                                        <i class="far fa-calendar-alt text-primary me-1"></i> 
                                        {{ $attendance->date->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        @if($attendance->clock_in)
                                            <i class="fas fa-sign-in-alt text-success me-1"></i> 
                                            {{ $attendance->clock_in }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->clock_out)
                                            <i class="fas fa-sign-out-alt text-danger me-1"></i> 
                                            {{ $attendance->clock_out }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'present' => 'success',
                                                'absent' => 'danger',
                                                'late' => 'warning',
                                                'half_day' => 'info',
                                                'vacation' => 'primary',
                                                'sick' => 'secondary'
                                            ][$attendance->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $attendance->notes }}">
                                            {{ $attendance->notes ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <span>{{ $attendance->user->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit attendance">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if(auth()->user()->role == 5)
                                            <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Are you sure you want to delete this attendance record?')"
                                                       title="Delete attendance">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-3">
                    {{ $attendances->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted mb-3">No attendance records found</p>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Record Your First Attendance
                    </a>
                </div>
            @endif
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
.badge {
    font-weight: 500;
    padding: 0.4em 0.6em;
}

/* Pagination styling */
.pagination {
    justify-content: center;
}
.page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.page-link {
    color: #0d6efd;
}
.page-link:hover {
    color: #0a58ca;
}
</style>
@endsection