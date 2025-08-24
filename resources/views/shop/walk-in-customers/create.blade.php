@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Register Walk-in Customer</h2>
    <a href="{{ route('shop.dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <form action="{{ route('shop.walk-in-customers.store') }}" method="POST">
        @csrf

        <input type="hidden" name="source" value="walk_in_customer">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>


        <div class="mb-3">
            <label>What does the customer need?</label>
            <input type="text" name="need" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Comment</label>
            <textarea name="comment" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Customer</button>
    </form>
</div>
@endsection
