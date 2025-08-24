@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
    <p><strong>Department:</strong> {{ auth()->user()->department?->name }}</p>
    <p><strong>Position:</strong> {{ auth()->user()->position }}</p>

    <a href="{{ route('profile.edit-password') }}" class="btn btn-primary mt-3">Change Password</a>
</div>
@endsection
