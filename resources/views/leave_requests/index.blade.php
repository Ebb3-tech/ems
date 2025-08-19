@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leave Requests</h1>
    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary mb-3">Request Leave</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Approved By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveRequests as $leave)
            <tr>
                <td>{{ $leave->user->name }}</td>
                <td>{{ ucfirst($leave->type) }}</td>
                <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                <td>{{ ucfirst($leave->status) }}</td>
                <td>{{ $leave->approver?->name ?? 'N/A' }}</td>
                <td>
                    @if(auth()->user()->can('approve-leave') && $leave->status == 'pending')
                    <form action="{{ route('leave-requests.update', $leave) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button name="status" value="approved" class="btn btn-sm btn-success">Approve</button>
                        <button name="status" value="rejected" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
