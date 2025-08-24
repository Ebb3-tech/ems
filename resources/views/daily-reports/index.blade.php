@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary"><i class="fas fa-file-alt me-2"></i>Daily Reports</h2>
        <div>
            @if(auth()->user()->role != 5)
                <a href="{{ route('daily-reports.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus-circle me-1"></i> Add New Report
                </a>
                <a href="{{ route('callcenter.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>
            @else
                <a href="{{ route('ceo.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                @if(auth()->user()->role == 5)
                    <span class="badge bg-info me-2">
                        <i class="fas fa-users me-1"></i> Admin View
                    </span>
                    Showing all employee reports
                @else
                    <span class="badge bg-primary me-2">
                        <i class="fas fa-user me-1"></i> Personal View
                    </span>
                    Your reports only
                @endif
            </h5>
            <span class="badge bg-primary">{{ $reports->total() }} reports</span>
        </div>
        <div class="card-body p-0">
            @if($reports->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Employee</th>
                                <th>Content</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td class="ps-3">
                                        <i class="far fa-calendar-alt text-muted me-1"></i> 
                                        {{ $report->created_at->format('Y-m-d') }}
                                        <div class="small text-muted">{{ $report->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>{{ $report->user->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 400px;">
                                            {{ Str::limit($report->content, 100) }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('daily-reports.show', $report) }}" class="btn btn-sm btn-outline-info me-1" title="View report">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        {{-- Edit: Only owner or CEO --}}
                                        @if(auth()->user()->id == $report->user_id || auth()->user()->role == 5)
                                            <a href="{{ route('daily-reports.edit', $report) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit report">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        {{-- Delete: Only CEO (role 5) --}}
                                        @if(auth()->user()->role == 5)
                                            <form action="{{ route('daily-reports.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Delete report">
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
                    {{ $reports->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-file-alt fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted mb-3">No daily reports found</p>
                    @if(auth()->user()->role != 5)
                        <a href="{{ route('daily-reports.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Create Your First Report
                        </a>
                    @endif
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
.btn-outline-info, .btn-outline-warning, .btn-outline-danger {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-outline-info:hover, .btn-outline-warning:hover, .btn-outline-danger:hover {
    color: white;
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