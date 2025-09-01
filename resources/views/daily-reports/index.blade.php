@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <h2 class="fw-bold text-primary h3 mb-2 mb-md-0"><i class="fas fa-file-alt me-2"></i>Daily Reports</h2>
        <div class="d-flex flex-wrap gap-2">
            @if(auth()->user()->role != 5)
                <a href="{{ route('daily-reports.create') }}" class="btn btn-primary btn-sm btn-md-normal">
                    <i class="fas fa-plus-circle me-1"></i> Add New Report
                </a>
                <a href="{{ route('callcenter.index') }}" class="btn btn-outline-secondary btn-sm btn-md-normal">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>
            @else
                <a href="{{ route('ceo.dashboard') }}" class="btn btn-outline-secondary btn-sm btn-md-normal">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white py-2 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
            <h5 class="mb-0 fs-6">
                @if(auth()->user()->role == 5)
                    <span class="badge bg-info me-2">
                        <i class="fas fa-users me-1"></i> Admin View
                    </span>
                    <span class="d-inline d-sm-inline">Showing all employee reports</span>
                @else
                    <span class="badge bg-primary me-2">
                        <i class="fas fa-user me-1"></i> Personal View
                    </span>
                    <span class="d-inline d-sm-inline">Your reports only</span>
                @endif
            </h5>
            <span class="badge bg-primary">{{ $reports->total() }} reports</span>
        </div>
        <div class="card-body p-0">
            @if($reports->count())
                <!-- Desktop view (table) - hidden on small screens -->
                <div class="d-none d-md-block">
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
                                            <div class="text-truncate" style="max-width: 300px;">
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
                </div>
                
                <!-- Mobile view (cards) - visible only on small screens -->
                <div class="d-md-none">
                    <div class="list-group list-group-flush">
                        @foreach($reports as $report)
                            <div class="list-group-item p-3 border-start-0 border-end-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>{{ $report->user->name }}</div>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>{{ $report->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="small text-muted mb-1">Content:</div>
                                    <div class="text-break">{{ Str::limit($report->content, 100) }}</div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('daily-reports.show', $report) }}" class="btn btn-sm btn-outline-info" title="View report">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    
                                    {{-- Edit: Only owner or CEO --}}
                                    @if(auth()->user()->id == $report->user_id || auth()->user()->role == 5)
                                        <a href="{{ route('daily-reports.edit', $report) }}" class="btn btn-sm btn-outline-warning" title="Edit report">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    @endif
                                    
                                    {{-- Delete: Only CEO (role 5) --}}
                                    @if(auth()->user()->role == 5)
                                        <form action="{{ route('daily-reports.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete report">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
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
    border: none;
}
.card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
.table th, .table td {
    padding: 0.75rem 1rem;
}
.text-break {
    word-wrap: break-word !important;
    word-break: break-word !important;
}

/* Table action buttons */
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

/* Mobile card action buttons */
@media (max-width: 767.98px) {
    .btn-outline-info, .btn-outline-warning, .btn-outline-danger {
        border-radius: 4px;
        width: auto;
        height: auto;
        padding: 0.25rem 0.5rem;
    }
}

/* Pagination styling */
.pagination {
    justify-content: center;
    flex-wrap: wrap;
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

/* COMPLETELY HIDE ALL PAGINATION ARROWS - Multiple methods to ensure removal */

/* Method 1: Hide first and last pagination items */
.pagination .page-item:first-child,
.pagination .page-item:last-child {
    display: none !important;
}

/* Method 2: Hide by rel attributes */
.pagination .page-item .page-link[rel="prev"],
.pagination .page-item .page-link[rel="next"] {
    display: none !important;
}

/* Method 3: Hide by aria-label attributes */
.pagination .page-item .page-link[aria-label*="Previous"],
.pagination .page-item .page-link[aria-label*="Next"] {
    display: none !important;
}

/* Method 4: Hide by content - covers Bootstrap default arrows */
.pagination .page-item .page-link:contains("‹"),
.pagination .page-item .page-link:contains("›"),
.pagination .page-item .page-link:contains("«"),
.pagination .page-item .page-link:contains("»"),
.pagination .page-item .page-link:contains("<"),
.pagination .page-item .page-link:contains(">") {
    display: none !important;
}

/* Method 5: Target Bootstrap's default Previous/Next classes */
.pagination .page-item:has(.page-link[aria-label*="Previous"]),
.pagination .page-item:has(.page-link[aria-label*="Next"]) {
    display: none !important;
}

/* Method 6: Hide any pseudo-elements that might contain arrows */
.pagination .page-link::before,
.pagination .page-link::after {
    content: none !important;
    display: none !important;
}

/* Method 7: Override Bootstrap's pagination arrows with empty content */
.pagination .page-item:first-child .page-link::before,
.pagination .page-item:last-child .page-link::after,
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    font-size: 0 !important;
    width: 0 !important;
    height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    visibility: hidden !important;
}

/* Method 8: Force hide by targeting common pagination structures */
nav[aria-label="pagination"] .page-item:first-child,
nav[aria-label="pagination"] .page-item:last-child,
.pagination-wrapper .page-item:first-child,
.pagination-wrapper .page-item:last-child {
    display: none !important;
}

/* Responsive button sizing */
@media (max-width: 767.98px) {
    .btn-sm.btn-md-normal {
        padding: .25rem .5rem;
        font-size: .875rem;
    }
    
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
    }
}

/* For extremely small screens */
@media (max-width: 359.98px) {
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
</div>

<script>
// JavaScript solution to remove pagination arrows after page load
document.addEventListener('DOMContentLoaded', function() {
    // Remove all pagination arrows using JavaScript
    const paginationItems = document.querySelectorAll('.pagination .page-item');
    
    paginationItems.forEach(function(item) {
        const link = item.querySelector('.page-link');
        if (link) {
            // Check for various arrow symbols and attributes
            const linkText = link.textContent.trim();
            const ariaLabel = link.getAttribute('aria-label');
            const rel = link.getAttribute('rel');
            
            // Remove items containing arrows or navigation text
            if (linkText === '‹' || linkText === '›' || 
                linkText === '«' || linkText === '»' || 
                linkText === '<' || linkText === '>' ||
                linkText === 'Previous' || linkText === 'Next' ||
                (ariaLabel && (ariaLabel.includes('Previous') || ariaLabel.includes('Next'))) ||
                rel === 'prev' || rel === 'next') {
                item.style.display = 'none';
                item.remove();
            }
        }
    });
    
    // Also remove first and last items as backup
    const pagination = document.querySelector('.pagination');
    if (pagination) {
        const firstItem = pagination.querySelector('.page-item:first-child');
        const lastItem = pagination.querySelector('.page-item:last-child');
        
        if (firstItem) firstItem.remove();
        if (lastItem && lastItem !== firstItem) lastItem.remove();
    }
});
</script>

@endsection