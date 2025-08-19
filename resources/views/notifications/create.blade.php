@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Notification</h1>

    <form action="{{ route('notifications.store') }}" method="POST">
        @csrf

        <div class="mb-3">
    <label>Send To</label>
    <select name="user_id" class="form-control" required>
        <option value="">-- Select User --</option>
        @foreach($users as $user)
            @if($user->id !== Auth::id())
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endif
        @endforeach
    </select>
</div>


        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>
@endsection
