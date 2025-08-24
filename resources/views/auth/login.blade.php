@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Login</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                class="form-control @error('email') is-invalid @enderror"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
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

                        <div class="mb-3 form-check">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                class="form-check-input" 
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                       <div class="mb-3 text-center">
    @if (Route::has('password.request'))
        <a class="text-decoration-none" href="{{ route('password.request') }}">
            Forgot Your Password?
        </a>
    @endif
</div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
