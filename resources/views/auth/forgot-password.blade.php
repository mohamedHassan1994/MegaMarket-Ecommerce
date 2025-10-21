@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="card shadow-lg" style="max-width: 900px; width: 100%; border-radius: 12px; overflow: hidden;">
    <div class="row g-0">
      <div class="col-md-6 p-5">
        <h3 class="mb-4 text-center">Forgot Password</h3>

        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>

        <p class="text-center mt-3 mb-0">
          <a href="{{ route('login') }}">Back to login</a>
        </p>
      </div>

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
