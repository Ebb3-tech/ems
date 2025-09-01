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
                <th>Reason</th> <!-- Added reason -->
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
                <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') }}</td>
                <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') }}</td>
                <td>{{ $leave->reason }}</td> <!-- Display reason -->
                <td>{{ ucfirst($leave->status) }}</td>
                <td>{{ $leave->approver?->name ?? 'N/A' }}</td>
                <td>
                    @if(auth()->user()->role == 5 && $leave->status == 'pending')
                        <!-- CEO can approve or reject -->
                        <form action="{{ route('leave-requests.update', $leave) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <button name="status" value="approved" class="btn btn-sm btn-success">Approve</button>
                            <button name="status" value="rejected" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    @elseif(auth()->id() == $leave->user_id && $leave->status == 'pending')
                        <!-- User can edit/delete their own pending requests -->
                        <a href="{{ route('leave-requests.edit', $leave) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('leave-requests.destroy', $leave) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
