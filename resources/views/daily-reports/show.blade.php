@extends('layouts.app')

@section('content')

<div class="container mt-3"> 
    {{-- Flash Message --}} 
    @if(session('success')) 
    <div class="alert alert-success"> 
        {{ session('success') }} 
    </div> 
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <h2 class="fw-bold text-primary h3">
            <i class="fas fa-file-alt me-2"></i>Daily Report Details
        </h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('daily-reports.index') }}" class="btn btn-outline-secondary btn-sm btn-md-normal">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>

            @if(auth()->user()->role == 5)
            <a href="{{ route('daily-reports.download', $report->id) }}" class="btn btn-outline-success btn-sm btn-md-normal">
                <i class="fas fa-download me-1"></i> Download
            </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="mb-0 fw-bold">Report Information</h5>
                    @if(auth()->user()->id == $report->user_id || auth()->user()->role == 5)
                        <a href="{{ route('daily-reports.edit', $report) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Report
                        </a>
                    @endif
                </div>
                <div class="card-body p-3 p-md-4">
                    {{-- User Info --}}
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-4 gap-2 gap-sm-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 p-md-3 me-sm-3">
                            <i class="fas fa-user fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Submitted by</div>
                            <h5 class="mb-0 fw-bold fs-6">{{ $report->user->name }}</h5>
                        </div>
                        <div class="ms-sm-auto mt-2 mt-sm-0 text-start text-sm-end">
                            <div class="text-muted small">Date</div>
                            <div class="fw-medium">
                                <i class="far fa-calendar-alt me-1"></i> {{ $report->report_date }}
                            </div>
                        </div>
                    </div>

                    {{-- Report Content --}}
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-align-left text-primary me-1"></i> Report Content
                        </h6>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0 text-break">{{ $report->content }}</p>
                        </div>
                    </div>

                    {{-- Attached Image --}}
                    @if(isset($report->image) && !empty($report->image))
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-image text-primary me-1"></i> Attached Image
                        </h6>
                        <div class="text-center bg-light rounded p-3">
                            <img src="{{ asset('storage/' . $report->image) }}" alt="Report Image" 
                                class="img-fluid rounded shadow-sm" style="max-height: 300px; max-width: 100%;">
                        </div>
                    </div>
                    @endif

                    {{-- Assign Marks (CEO only) --}}
                    @if(auth()->user()->role == 5)
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2"><i class="fas fa-star text-primary me-1"></i> Assigned Marks</h6>
                        <form action="{{ route('daily-reports.assignMarks', $report->id) }}" method="POST" class="d-flex flex-column flex-sm-row gap-2">
                            @csrf
                            <input type="number" name="marks" min="0" max="100" class="form-control" 
                                placeholder="Enter marks" value="{{ $report->marks ?? '' }}" required>
                            <button type="submit" class="btn btn-success">Assign marks</button>
                        </form>
                    </div>
                    @endif

                    {{-- Display Marks --}}
                    @if(isset($report->marks))
                    <div>
                        <strong>Marks:</strong> {{ $report->marks }}/100
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white d-flex justify-content-between py-3">
                    <div class="text-muted small">
                        <i class="far fa-clock me-1"></i> Submitted {{ $report->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- User's Other Reports --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2"></i> {{ $report->user->name }}'s Reports
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($userReports as $userReport)
                            <a href="{{ route('daily-reports.show', $userReport) }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center py-3 px-3 
                                      {{ $userReport->id == $report->id ? 'active bg-primary text-white' : '' }}">
                                <div class="me-3">
                                    <span class="{{ $userReport->id == $report->id ? 'bg-white text-primary' : 'bg-light text-primary' }} rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="far fa-file-alt"></i>
                                    </span>
                                </div>
                                <div class="text-break flex-grow-1">
                                    <div class="{{ $userReport->id == $report->id ? 'text-white-50' : 'text-muted' }} small">
                                        {{ $userReport->report_date }}
                                    </div>
                                    <div class="fw-medium text-truncate" style="max-width: 180px;">
                                        {{ \Illuminate\Support\Str::limit($userReport->content, 30) }}
                                    </div>
                                </div>
                                @if($userReport->marks)
                                    <span class="ms-2 badge {{ $userReport->id == $report->id ? 'bg-white text-primary' : 'bg-primary' }}">
                                        {{ $userReport->marks }}/100
                                    </span>
                                @endif
                            </a>
                        @empty
                            <div class="list-group-item py-3 px-3 text-center text-muted">
                                <i class="fas fa-info-circle me-2"></i> No reports found
                            </div>
                        @endforelse
                    </div>
                </div>
                @if(count($userReports) > 5)
                    <div class="card-footer bg-white text-center py-2">
                        <a href="{{ route('daily-reports.index', ['user_id' => $report->user_id]) }}" class="text-primary text-decoration-none">
                            <i class="fas fa-list me-1"></i> View all reports
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Font Awesome --}}
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
    .text-break {
        word-wrap: break-word !important;
        word-break: break-word !important;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa;
        z-index: 1;
    }
    .list-group-item-action.active:hover {
        background-color: var(--bs-primary);
    }
    @media (max-width: 768px) {
        .btn-sm.btn-md-normal {
            padding: .25rem .5rem;
            font-size: .875rem;
        }
    }
    /* Make action buttons full width on very small screens */
    @media (max-width: 375px) {
        .d-flex.flex-wrap.gap-2 {
            flex-direction: column;
            width: 100%;
        }
        .d-flex.flex-wrap.gap-2 a {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection