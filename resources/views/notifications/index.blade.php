@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notifications</h1>
    <a href="{{ route('notifications.create') }}" class="btn btn-primary mb-3">Create Notification</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Message</th>
                <th>To</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $notification)
                <tr>
                    <td>{{ $notification->title }}</td>
                    <td>{{ $notification->message }}</td>
                    <td>{{ $notification->user->name ?? 'N/A' }}</td>
                    <td>{{ $notification->sender->name ?? 'System' }}</td>
                    <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('notifications.show', $notification) }}" class="btn btn-info btn-sm">View</a>
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No notifications found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
