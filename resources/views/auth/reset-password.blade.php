@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="card shadow-lg" style="max-width: 500px; width: 100%; border-radius: 12px;">
    <div class="card-body p-5">
      <h3 class="text-center mb-4">Reset Password</h3>

      @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
          <label for="password" class="form-label">New Password</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror"
                 id="password" name="password" required autofocus>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirm New Password</label>
          <input type="password" class="form-control" id="password_confirmation"
                 name="password_confirmation" required>
          <div id="confirmFeedback" class="invalid-feedback d-none">
            Passwords do not match.
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
      </form>

      <p class="text-center mt-3 mb-0">
        <a href="{{ route('login') }}">Back to login</a>
      </p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  const p = document.getElementById('password');
  const c = document.getElementById('password_confirmation');
  const f = document.getElementById('confirmFeedback');

  function check() {
    if (c.value.length > 0 && c.value !== p.value) {
      c.classList.add('is-invalid'); f.classList.remove('d-none');
    } else {
      c.classList.remove('is-invalid'); f.classList.add('d-none');
    }
  }
  p.addEventListener('input', check);
  c.addEventListener('input', check);
});
</script>
@endpush
