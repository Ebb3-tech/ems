@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Reset Password</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $request->email) }}" 
                                required 
                                autofocus 
                                class="form-control @error('email') is-invalid @enderror"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                class="form-control @error('password') is-invalid @enderror"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                class="form-control"
                            >
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>

                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
