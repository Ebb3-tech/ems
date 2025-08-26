@extends('layouts.app')

@section('content')
<div class="container mt-3 d-flex flex-column min-vh-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">Hamubere Dashboard</h2>
        <div class="d-flex">
            <a href="{{ route('callcenter.create') }}" class="btn btn-sm btn-primary me-2">
                <i class="fas fa-plus-circle me-1"></i> New Customer
            </a>
            <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-warning me-2">
                <i class="fas fa-clock me-1"></i> Attendance
            </a>
            <a href="{{ route('daily-reports.index') }}" class="btn btn-sm btn-info text-white">
                <i class="fas fa-file-alt me-1"></i> Report
            </a>
        </div>
    </div>

    {{-- Dashboard Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body bg-primary text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Customers</h6>
                            <h3 class="mb-0 fw-bold" id="total-customers">{{ \App\Models\Customer::count() }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body bg-success text-white rounded-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Requests</h6>
                            <h3 class="mb-0 fw-bold" id="total-requests">{{ \App\Models\CustomerRequest::count() }}</h3>
                        </div>
                        <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <a href="{{ route('callcenter.my-tasks') }}" class="text-decoration-none">
                <div class="card border-0 rounded-3 shadow-sm">
                    <div class="card-body bg-dark text-white rounded-3 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">My Tasks</h6>
                                <h3 class="mb-0 fw-bold" id="total-tasks">{{ $tasks->count() }}</h3>
                            </div>
                            <i class="fas fa-tasks fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 rounded-3 shadow-sm">
                <div class="card-body bg-light py-2">
                    <div class="row g-1">
                        @foreach(['pending'=>'warning','processing'=>'secondary','completed'=>'info','canceled'=>'danger'] as $status => $color)
                        <div class="col-3">
                            <div class="card border-0 rounded-2 clickable-card" data-status="{{ $status }}">
                                <div class="card-body bg-{{ $color }} text-white rounded-2 p-1">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="card-title mb-0 small">{{ ucfirst($status)[0] }}</span>
                                        <h5 class="mb-0 fw-bold" id="{{ $status }}-requests">{{ \App\Models\CustomerRequest::where('status',$status)->count() }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-user text-muted"></i>
                        </span>
                        <input type="text" id="name-filter" class="form-control border-start-0" placeholder="Filter by name">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-phone text-muted"></i>
                        </span>
                        <input type="text" id="phone-filter" class="form-control border-start-0" placeholder="Filter by phone">
                    </div>
                </div>
                <div class="col-md-2">
                    <select id="source-filter" class="form-select form-select-sm">
                        <option value="">All Sources</option>
                        <option value="walk_in_customer">Walk-in</option>
                        <option value="phone">Phone</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                        <option value="twitter">Twitter</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="telegram">Telegram</option>
                        <option value="email">Email</option>
                        <option value="linkedin">LinkedIn</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="status-filter" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach(['pending','processing','completed','canceled'] as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Requests Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Latest Requests</h5>
            <span id="filter-count" class="badge bg-primary"></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Customer</th>
                            <th>Phone</th>
                            <th>Needs</th>
                            <th>Date</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th class="text-center">History</th>
                        </tr>
                    </thead>
                    <tbody id="requests-table-body">
                        @foreach($requests->sortByDesc('created_at') as $req)
                        <tr>
                            <td class="ps-3 fw-medium">{{ $req->customer->name }}</td>
                            <td>{{ $req->customer->phone }}</td>
                            <td>{{ $req->need }}</td>
                            <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    @if($req->source == 'walk_in_customer')
                                        <i class="fas fa-store me-1"></i> Walk-in
                                    @elseif($req->source == 'phone')
                                        <i class="fas fa-phone me-1"></i> Phone
                                    @elseif($req->source == 'facebook')
                                        <i class="fab fa-facebook me-1"></i> Facebook
                                    @elseif($req->source == 'instagram')
                                        <i class="fab fa-instagram me-1"></i> Instagram
                                    @elseif($req->source == 'tiktok')
                                        <i class="fab fa-tiktok me-1"></i> TikTok
                                    @elseif($req->source == 'twitter')
                                        <i class="fab fa-twitter me-1"></i> Twitter
                                    @elseif($req->source == 'whatsapp')
                                        <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                    @elseif($req->source == 'telegram')
                                        <i class="fab fa-telegram me-1"></i> Telegram
                                    @elseif($req->source == 'email')
                                        <i class="fas fa-envelope me-1"></i> Email
                                    @elseif($req->source == 'linkedin')
                                        <i class="fab fa-linkedin me-1"></i> LinkedIn
                                    @else
                                        <i class="fas fa-globe me-1"></i> {{ ucfirst($req->source) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <select class="form-select form-select-sm status-dropdown me-1" data-id="{{ $req->id }}">
                                        @foreach(['pending','processing','completed','canceled'] as $status)
                                            <option value="{{ $status }}" {{ $req->status==$status?'selected':'' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-primary update-status-btn" data-id="{{ $req->id }}">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('customers.history', $req->customer->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="View details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Copyright Footer --}}
    <div class="mt-auto py-3 text-center border-top">
        <p class="text-muted mb-0">
            <i class="far fa-copyright me-1"></i>2025Designed by Ebenezer. All Rights Reserved.
        </p>
    </div>
</div>

{{-- Add Font Awesome if not already included in your layout --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.clickable-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.clickable-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.1) !important;
}
.card {
    overflow: hidden;
}
.table th, .table td {
    padding: 0.6rem 0.75rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameFilter = document.getElementById('name-filter');
    const phoneFilter = document.getElementById('phone-filter');
    const sourceFilter = document.getElementById('source-filter');
    const statusFilter = document.getElementById('status-filter');
    const filterCount = document.getElementById('filter-count');
    
    // Set initial count
    updateFilterCount({{ $requests->count() }});

    function filterRequests() {
        // Get filter values
        const name = nameFilter.value.trim();
        const phone = phoneFilter.value.trim();
        const source = sourceFilter.value;
        const status = statusFilter.value;
        
        // Server-side filtering for accurate data
        const params = new URLSearchParams();
        if (name) params.append('name', name);
        if (phone) params.append('phone', phone);
        if (source) params.append('source', source);
        if (status) params.append('status', status);
        // Ensure newest customers are on top
        params.append('sort', 'newest');
        
        // Show loading indicator
        const tbody = document.getElementById('requests-table-body');
        if (name || phone || source || status) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Loading...</td></tr>';
        }
        
        fetch(`/callcenter/requests-filter?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                updateFilterCount(data.length);
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-3">No matching records found</td></tr>';
                    return;
                }

                data.forEach(req => {
                    let sourceIcon = '';
                    if(req.source == 'walk_in_customer') {
                        sourceIcon = '<i class="fas fa-store me-1"></i> Walk-in';
                    } else if(req.source == 'phone') {
                        sourceIcon = '<i class="fas fa-phone me-1"></i> Phone';
                    } else if(req.source == 'facebook') {
                        sourceIcon = '<i class="fab fa-facebook me-1"></i> Facebook';
                    } else if(req.source == 'instagram') {
                        sourceIcon = '<i class="fab fa-instagram me-1"></i> Instagram';
                    } else if(req.source == 'tiktok') {
                        sourceIcon = '<i class="fab fa-tiktok me-1"></i> TikTok';
                    } else if(req.source == 'twitter') {
                        sourceIcon = '<i class="fab fa-twitter me-1"></i> Twitter';
                    } else if(req.source == 'whatsapp') {
                        sourceIcon = '<i class="fab fa-whatsapp me-1"></i> WhatsApp';
                    } else if(req.source == 'telegram') {
                        sourceIcon = '<i class="fab fa-telegram me-1"></i> Telegram';
                    } else if(req.source == 'email') {
                        sourceIcon = '<i class="fas fa-envelope me-1"></i> Email';
                    } else if(req.source == 'linkedin') {
                        sourceIcon = '<i class="fab fa-linkedin me-1"></i> LinkedIn';
                    } else {
                        sourceIcon = `<i class="fas fa-globe me-1"></i> ${req.source.charAt(0).toUpperCase() + req.source.slice(1)}`;
                    }
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="ps-3 fw-medium">${req.customer.name}</td>
                        <td>${req.customer.phone}</td>
                        <td>${req.need}</td>
                        <td>${new Date(req.created_at).toLocaleString()}</td>
                        <td><span class="badge bg-light text-dark">${sourceIcon}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <select class="form-select form-select-sm status-dropdown me-1" data-id="${req.id}">
                                    <option value="pending" ${req.status=='pending'?'selected':''}>Pending</option>
                                    <option value="processing" ${req.status=='processing'?'selected':''}>Processing</option>
                                    <option value="completed" ${req.status=='completed'?'selected':''}>Completed</option>
                                    <option value="canceled" ${req.status=='canceled'?'selected':''}>Canceled</option>
                                </select>
                                <button class="btn btn-sm btn-primary update-status-btn" data-id="${req.id}">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="/customers/history/${req.customer.id}" class="btn btn-sm btn-outline-info rounded-circle" title="View details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    `;
                    tbody.appendChild(row);
                    
                    // Reattach event listeners to the new buttons
                    const newUpdateBtn = row.querySelector('.update-status-btn');
                    if(newUpdateBtn) {
                        newUpdateBtn.addEventListener('click', handleStatusUpdate);
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching filtered data:', error);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-3 text-danger">Error loading data. Please try again.</td></tr>';
            });
    }
    
    function updateFilterCount(count) {
        filterCount.textContent = `${count} request${count !== 1 ? 's' : ''} found`;
    }
    
    function handleStatusUpdate(e) {
        const btn = e.target.classList.contains('update-status-btn') ? e.target : e.target.closest('.update-status-btn');
        const row = btn.closest('tr');
        const dropdown = row.querySelector('.status-dropdown');
        const newStatus = dropdown.value;
        const requestId = dropdown.getAttribute('data-id');

        fetch(`/callcenter/status/${requestId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({status: newStatus})
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Show success indicator
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-save"></i>';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                }, 1000);
                
                // Update status counters
                fetch('/callcenter/status-counts')
                    .then(res => res.json())
                    .then(counts => {
                        document.getElementById('pending-requests').textContent = counts.pending;
                        document.getElementById('processing-requests').textContent = counts.processing;
                        document.getElementById('completed-requests').textContent = counts.completed;
                        document.getElementById('canceled-requests').textContent = counts.canceled;
                        document.getElementById('total-requests').textContent = counts.total;
                    });
            } else {
                // Show error indicator
                btn.innerHTML = '<i class="fas fa-times"></i>';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-danger');
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-save"></i>';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-primary');
                }, 1000);
            }
        });
    }
    
    // Add debounce to prevent too many requests
    let debounceTimer;
    const debounce = (callback, time) => {
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(callback, time);
    };
    
    nameFilter.addEventListener('input', () => debounce(filterRequests, 500));
    phoneFilter.addEventListener('input', () => debounce(filterRequests, 500));
    sourceFilter.addEventListener('change', filterRequests);
    statusFilter.addEventListener('change', filterRequests);

    // Status card click handlers
    document.querySelectorAll('.clickable-card').forEach(card => {
        card.addEventListener('click', function() {
            // Clear other filters first
            nameFilter.value = '';
            phoneFilter.value = '';
            sourceFilter.value = '';
            
            // Set status filter
            statusFilter.value = this.getAttribute('data-status');
            filterRequests();
        });
    });

    // Attach event listeners to initial update buttons
    document.querySelectorAll('.update-status-btn').forEach(btn => {
        btn.addEventListener('click', handleStatusUpdate);
    });
    
    // Add clear filters button functionality
    document.addEventListener('keydown', function(e) {
        // Clear filters with Escape key
        if (e.key === 'Escape') {
            nameFilter.value = '';
            phoneFilter.value = '';
            sourceFilter.value = '';
            statusFilter.value = '';
            filterRequests();
        }
    });
    
    // Add a clear filters button
    const filterCard = document.querySelector('.card.shadow-sm.mb-3');
    const clearBtn = document.createElement('button');
    clearBtn.className = 'btn btn-sm btn-outline-secondary position-absolute';
    clearBtn.style.right = '10px';
    clearBtn.style.top = '10px';
    clearBtn.innerHTML = '<i class="fas fa-times"></i> Clear';
    clearBtn.addEventListener('click', function() {
        nameFilter.value = '';
        phoneFilter.value = '';
        sourceFilter.value = '';
        statusFilter.value = '';
        filterRequests();
    });
    filterCard.style.position = 'relative';
    filterCard.appendChild(clearBtn);
});
</script>
@endsection