@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $notification->title }}</h1>
    <p><strong>To:</strong> {{ $notification->user->name ?? 'N/A' }}</p>
    <p><strong>Message:</strong> {{ $notification->message }}</p>
    <p><strong>Created By:</strong> {{ $notification->sender->name ?? 'System' }}</p>
    <p><strong>Date:</strong> {{ $notification->created_at->format('d M Y H:i') }}</p>

    <hr>

    <h3>Replies</h3>
    @if($notification->replies->count())
        <ul class="list-group mb-4">
            @foreach($notification->replies as $reply)
                <li class="list-group-item">
                    <strong>{{ $reply->sender->name }}</strong> to <strong>{{ $reply->receiver->name }}</strong>:
                    <p>{{ $reply->message }}</p>
                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <p>No replies yet.</p>
    @endif

    @if(auth()->id() !== $notification->created_by)
        <h3>Reply to {{ $notification->sender->name ?? 'System' }}</h3>

        <form action="{{ route('notifications.reply', $notification->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="message" class="form-label">Your Reply</label>
                <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Reply</button>
        </form>
    @endif

    <a href="{{ route('notifications.index') }}" class="btn btn-secondary mt-4">Back</a>
</div>
@endsection
