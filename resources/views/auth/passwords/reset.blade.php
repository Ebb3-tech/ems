@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
@endsection
