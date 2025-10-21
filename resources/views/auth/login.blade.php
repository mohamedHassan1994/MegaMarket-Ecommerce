@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg" style="max-width: 900px; width: 100%; border-radius: 12px; overflow: hidden;">
        <div class="row g-0">

            <!-- Left Side: Form -->
            <div class="col-md-6 p-5">
                <h3 class="mb-4 text-center">Login to Your Account</h3>

                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Remember Me + Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="text-center mt-3 mb-2">
                    Don’t have an account? <a href="{{ route('register') }}">Register</a>
                </p>

                <!-- Social Login Section -->
                <div class="text-center my-3">
                    <div class="d-flex align-items-center gap-2">
                        <hr class="flex-grow-1">
                        <span class="fw-bold text-muted">Other login options</span>
                        <hr class="flex-grow-1">
                    </div>

                    <div class="mt-3 d-flex justify-content-center gap-2 flex-wrap">
                        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-light d-flex align-items-center gap-2">
                            <img src="https://developers.google.com/identity/images/g-logo.png" 
                                alt="Google" style="width:24px; height:24px;">
                            Google
                        </a>

                        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-primary d-flex align-items-center gap-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png" 
                                alt="Facebook" style="width:24px; height:24px;">
                            Facebook
                        </a>

                        <a href="{{ route('social.redirect', 'github') }}" class="btn btn-dark d-flex align-items-center gap-2">
                            <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" 
                                alt="GitHub" style="width:24px; height:24px; object-fit:contain">
                            GitHub
                        </a>
                        
                        <a href="{{ route('social.redirect', 'twitter') }}" class="btn btn-info d-flex align-items-center gap-2">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/twitter.svg" 
                                alt="Twitter" style="width:24px; height:24px;object-fit:contain">
                            Twitter
                        </a>

                    </div>
                </div>


            </div>

            <!-- Right Side: Image -->
            <div class="col-md-6 d-none d-md-block position-relative" style="min-height:560px;">
                <img src="{{ asset('images/shopping.jpg') }}"
                     class="position-absolute top-0 start-0 w-100 h-100"
                     style="object-fit: contain;"
                     alt="Shopping">
            </div>
        </div>
    </div>
</div>
@endsection
