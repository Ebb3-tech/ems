@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-file-alt me-2"></i>Daily Report Details
        </h2>
        <div>
            <a href="{{ route('daily-reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Reports
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Report Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-user fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Submitted by</div>
                            <h5 class="mb-0 fw-bold">{{ $report->user->name }}</h5>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="text-muted small">Date</div>
                            <div class="fw-medium">
                                <i class="far fa-calendar-alt me-1"></i> {{ $report->report_date }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-align-left text-primary me-1"></i> Report Content
                        </h6>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0">{{ $report->content }}</p>
                        </div>
                    </div>

                    @if(isset($report->image) && !empty($report->image))
                    <div>
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-image text-primary me-1"></i> Attached Image
                        </h6>
                        <div class="text-center bg-light rounded p-3">
                            <img src="{{ asset('storage/' . $report->image) }}" alt="Report Image" 
                                 class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white d-flex justify-content-between py-3">
                    <div class="text-muted small">
                        <i class="far fa-clock me-1"></i> Submitted {{ $report->created_at->diffForHumans() }}
                    </div>
                    
                    @if(auth()->user()->id == $report->user_id || auth()->user()->role == 5)
                    <div>
                        <a href="{{ route('daily-reports.edit', $report) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Report
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>Report Details
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex">
                            <div class="me-3">
                                <span class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="far fa-calendar-alt text-primary"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-muted small">Report Date</div>
                                <div class="fw-medium">{{ $report->report_date }}</div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex">
                            <div class="me-3">
                                <span class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-primary"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-muted small">Employee</div>
                                <div class="fw-medium">{{ $report->user->name }}</div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex">
                            <div class="me-3">
                                <span class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="far fa-clock text-primary"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-muted small">Created At</div>
                                <div class="fw-medium">{{ $report->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </li>
                        @if($report->updated_at && $report->updated_at->ne($report->created_at))
                        <li class="list-group-item px-0 py-3 d-flex">
                            <div class="me-3">
                                <span class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-edit text-primary"></i>
                                </span>
                            </div>
                            <div>
                                <div class="text-muted small">Last Updated</div>
                                <div class="fw-medium">{{ $report->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </li>
                        @endif
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
    border: none;
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.list-group-item {
    border-left: 0;
    border-right: 0;
    border-color: #f5f5f5;
}
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection