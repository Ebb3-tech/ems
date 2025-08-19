@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Call Center Dashboard</h1>

    {{-- Dashboard Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Customers</h5>
                    <p class="card-text fs-2" id="total-customers">{{ \App\Models\Customer::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Requests</h5>
                    <p class="card-text fs-2" id="total-requests">{{ \App\Models\CustomerRequest::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-warning h-100 shadow-sm clickable-card" data-status="pending">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <p class="card-text fs-2" id="pending-requests">{{ \App\Models\CustomerRequest::where('status','pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-secondary h-100 shadow-sm clickable-card" data-status="processing">
                <div class="card-body">
                    <h5 class="card-title">Processing</h5>
                    <p class="card-text fs-2" id="processing-requests">{{ \App\Models\CustomerRequest::where('status','processing')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-info h-100 shadow-sm clickable-card" data-status="completed">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <p class="card-text fs-2" id="completed-requests">{{ \App\Models\CustomerRequest::where('status','completed')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-danger h-100 shadow-sm clickable-card" data-status="canceled">
                <div class="card-body">
                    <h5 class="card-title">Canceled</h5>
                    <p class="card-text fs-2" id="canceled-requests">{{ \App\Models\CustomerRequest::where('status','canceled')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-4 mb-5">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('callcenter.create') }}" class="btn btn-primary w-100 py-3 shadow-sm">
                    Add New Request
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attendance.create') }}" class="btn btn-warning w-100 py-3 shadow-sm">
                    Mark Attendance
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('daily-reports.index') }}" class="btn btn-info w-100 py-3 shadow-sm text-white">
                    Submit Report
                </a>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="search-term" class="form-control w-50 me-2" placeholder="Search by name, phone or social media">
        <select id="status-filter" class="form-select w-25">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="canceled">Canceled</option>
        </select>
    </div>

    {{-- Latest Requests Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Needs</th>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="requests-table-body">
                @foreach(\App\Models\CustomerRequest::with('customer')->latest()->take(5)->get() as $req)
                <tr>
                    <td>{{ $req->customer->name }}</td>
                    <td>{{ $req->customer->phone }}</td>
                    <td>{{ $req->need }}</td>
                    <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $req->source }}</td> 
                    <td>
                        <div class="d-flex align-items-center">
                            <select class="form-select status-dropdown me-2" data-id="{{ $req->id }}">
                                <option value="pending" {{ $req->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="processing" {{ $req->status=='processing'?'selected':'' }}>Processing</option>
                                <option value="completed" {{ $req->status=='completed'?'selected':'' }}>Completed</option>
                                <option value="canceled" {{ $req->status=='canceled'?'selected':'' }}>Canceled</option>
                            </select>

                            <button class="btn btn-sm btn-primary update-status-btn" data-id="{{ $req->id }}">
                                Update
                            </button>

                           
                        </div>
                    </td>

                    <td>
    <a href="{{ route('customers.history', $req->customer->id) }}" class="btn btn-sm btn-outline-info">
        View History
    </a>
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Update top cards
    function updateCards() {
        fetch('/callcenter/cards-counts')
            .then(res => res.json())
            .then(data => {
                document.getElementById('total-customers').innerText = data.total_customers;
                document.getElementById('total-requests').innerText = data.total_requests;
                document.getElementById('pending-requests').innerText = data.pending_requests;
                document.getElementById('processing-requests').innerText = data.processing_requests;
                document.getElementById('completed-requests').innerText = data.completed_requests;
                document.getElementById('canceled-requests').innerText = data.canceled_requests;
            });
    }

    // Update status via button
    document.getElementById('requests-table-body').addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('update-status-btn')) {
            const row = e.target.closest('tr');
            const dropdown = row.querySelector('.status-dropdown');
            const badge = row.querySelector('.status-badge');
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
                    badge.innerText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    badge.className = 'badge status-badge ms-2 ' +
                        (newStatus==='pending'?'bg-warning':
                        (newStatus==='processing'?'bg-secondary':
                        (newStatus==='completed'?'bg-info':'bg-danger')));
                    updateCards();
                } else {
                    alert('Failed to update status.');
                }
            });
        }
    });

    // Search/filter
    const searchInput = document.getElementById('search-term');
    const statusFilter = document.getElementById('status-filter');

    function filterRequests() {
        const term = searchInput.value;
        const status = statusFilter.value;

        fetch(`/callcenter/requests-filter?term=${term}&status=${status}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('requests-table-body');
                tbody.innerHTML = '';

                data.forEach(req => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${req.customer.name}</td>
                        <td>${req.customer.phone}</td>
                        <td>${req.need}</td>
                        <td>${new Date(req.created_at).toLocaleString()}</td>
                        <td>${req.source}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <select class="form-select status-dropdown me-2" data-id="${req.id}">
                                    <option value="pending" ${req.status=='pending'?'selected':''}>Pending</option>
                                    <option value="processing" ${req.status=='processing'?'selected':''}>Processing</option>
                                    <option value="completed" ${req.status=='completed'?'selected':''}>Completed</option>
                                    <option value="canceled" ${req.status=='canceled'?'selected':''}>Canceled</option>
                                </select>
                                <button class="btn btn-sm btn-primary update-status-btn" data-id="${req.id}">Update</button>
                               
                            </div>
                        </td>
                        
                        <td>
    <a href="{{ route('customers.history', $req->customer->id) }}" class="btn btn-sm btn-outline-info">
        View History
    </a> 
</td>
                    `;
                    tbody.appendChild(row);
                });
            });
    }

    searchInput.addEventListener('input', filterRequests);
    statusFilter.addEventListener('change', filterRequests);

    // Clickable cards filter
    document.querySelectorAll('.clickable-card').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            statusFilter.value = this.getAttribute('data-status');
            filterRequests();
        });
    });

});
</script>
@endsection

