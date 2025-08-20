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
        @foreach(['pending'=>'warning','processing'=>'secondary','completed'=>'info','canceled'=>'danger'] as $status => $color)
        <div class="col-md-2">
            <div class="card text-white bg-{{ $color }} h-100 shadow-sm clickable-card" data-status="{{ $status }}">
                <div class="card-body">
                    <h5 class="card-title">{{ ucfirst($status) }}</h5>
                    <p class="card-text fs-2" id="{{ $status }}-requests">{{ \App\Models\CustomerRequest::where('status',$status)->count() }}</p>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-md-3">
            <a href="{{ route('callcenter.my-tasks') }}" class="text-decoration-none">
                <div class="card text-white bg-dark h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">My Tasks</h5>
                        <p class="card-text fs-2" id="total-tasks">{{ $tasks->count() }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-4 mb-5">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('callcenter.create') }}" class="btn btn-primary w-100 py-3 shadow-sm">Add New Request</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attendance.create') }}" class="btn btn-warning w-100 py-3 shadow-sm">Mark Attendance</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('daily-reports.index') }}" class="btn btn-info w-100 py-3 shadow-sm text-white">Submit Report</a>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="search-term" class="form-control w-50 me-2" placeholder="Search by name, phone or social media">
        <select id="status-filter" class="form-select w-25">
            <option value="">All Statuses</option>
            @foreach(['pending','processing','completed','canceled'] as $status)
                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
            @endforeach
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
                    <th>History</th>
                </tr>
            </thead>
            <tbody id="requests-table-body">
                @foreach($requests as $req)
                <tr>
                    <td>{{ $req->customer->name }}</td>
                    <td>{{ $req->customer->phone }}</td>
                    <td>{{ $req->need }}</td>
                    <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $req->source }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <select class="form-select status-dropdown me-2" data-id="{{ $req->id }}">
                                @foreach(['pending','processing','completed','canceled'] as $status)
                                    <option value="{{ $status }}" {{ $req->status==$status?'selected':'' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary update-status-btn" data-id="{{ $req->id }}">Update</button>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('customers.history', $req->customer->id) }}" class="btn btn-sm btn-outline-info">View History</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
                            <a href="/customers/history/${req.customer.id}" class="btn btn-sm btn-outline-info">View History</a>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });
    }

    searchInput.addEventListener('input', filterRequests);
    statusFilter.addEventListener('change', filterRequests);

    document.querySelectorAll('.clickable-card').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            statusFilter.value = this.getAttribute('data-status');
            filterRequests();
        });
    });

    // Update status button
    document.getElementById('requests-table-body').addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('update-status-btn')) {
            const row = e.target.closest('tr');
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
                    filterRequests(); // refresh table
                } else {
                    alert('Failed to update status.');
                }
            });
        }
    });
});
</script>
@endsection
