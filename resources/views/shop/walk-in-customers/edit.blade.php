@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Walk-in Customer</h2>
<form action="{{ route('shop.walk-in-customers.update', $walk_in_customer->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ $walk_in_customer->name }}" required>
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ $walk_in_customer->phone }}">
    </div>

    <div class="mb-3">
        <label>Need</label>
        <input type="text" name="need" class="form-control" value="{{ $walk_in_customer->need }}">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="pending" {{ $walk_in_customer->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ $walk_in_customer->status == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="completed" {{ $walk_in_customer->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Comment</label>
        <textarea name="comment" class="form-control">{{ $walk_in_customer->comment }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Customer</button>
</form>


</div>
@endsection
