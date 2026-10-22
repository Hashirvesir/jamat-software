@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="card-header bg-primary text-white text-center py-3">
                <img src="{{ asset('logo.jpeg') }}" alt="Sindhi Halai Jamat Logo" class="rounded-circle bg-white p-1 shadow-sm mb-2" style="width: 75px; height: 75px; object-fit: contain;">
                <h4 class="mb-1 fw-bold">Sindhi Halai Jamat</h4>
                <div class="small text-white-50">{{ __('Register with Jamaat Management') }}</div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                    </div>

                    @if (Route::has('login'))
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}"
                                class="text-primary">{{ __('Already have an account? Login here') }}</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
