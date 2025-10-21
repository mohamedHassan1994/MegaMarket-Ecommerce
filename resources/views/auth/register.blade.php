@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg" style="max-width: 900px; width: 100%; border-radius: 12px; overflow: hidden;">
        <div class="row g-0">
            
            <!-- Left Side: Form -->
            <div class="col-md-6 p-5">
                <h3 class="mb-4 text-center">Create an Account</h3>

                <form id="registerForm" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- First & Last Name -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" 
                                   class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" 
                                   class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Password & Confirm Password -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" 
                                class="form-control" 
                                id="password_confirmation" 
                                name="password_confirmation" required>
                            <div id="confirmFeedback" class="invalid-feedback d-none">
                                Passwords do not match.
                            </div>
                        </div>
                    </div>

                    <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        let password = document.getElementById("password");
                        let confirm = document.getElementById("password_confirmation");
                        let feedback = document.getElementById("confirmFeedback");

                        function validatePasswordMatch() {
                            if (confirm.value.length > 0) {
                                if (password.value !== confirm.value) {
                                    confirm.classList.add("is-invalid");
                                    feedback.classList.remove("d-none");
                                } else {
                                    confirm.classList.remove("is-invalid");
                                    feedback.classList.add("d-none");
                                }
                            } else {
                                confirm.classList.remove("is-invalid");
                                feedback.classList.add("d-none");
                            }
                        }

                        password.addEventListener("input", validatePasswordMatch);
                        confirm.addEventListener("input", validatePasswordMatch);
                    });
                    </script>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" 
                            class="form-control @error('full_phone') is-invalid @enderror"
                            id="phone" name="phone" value="{{ old('phone') }}" required>

                        <div id="phoneFeedback" class="invalid-feedback d-none"></div>

                        @error('full_phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <input type="hidden" id="full_phone" name="full_phone" value="{{ old('full_phone') }}">
                    </div>


                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Already have an account? <a href="{{ route('login') }}">Login</a>
                </p>
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

@push('styles')
<!-- intl-tel-input CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.min.css"/>
<style>.iti { z-index: 10000; }</style>
@endpush

@push('scripts')
<!-- intl-tel-input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  var phoneInput = document.querySelector("#phone");
  if (!phoneInput) return;

  var iti = window.intlTelInput(phoneInput, {
    initialCountry: "eg",
    separateDialCode: true,
    preferredCountries: ["eg","sa","us","ae"],
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js"
  });

  var form = document.querySelector("#registerForm");
  form.addEventListener("submit", function (e) {
    var phoneFeedback = document.querySelector("#phoneFeedback");
    phoneInput.classList.remove("is-invalid");
    phoneFeedback.textContent = "";
    phoneFeedback.classList.add("d-none");

    if (!iti.isValidNumber()) {
      e.preventDefault();
      phoneInput.classList.add("is-invalid");
      phoneFeedback.textContent = "Please enter a valid phone number.";
      phoneFeedback.classList.remove("d-none");
      phoneInput.focus();
      return false;
    }

    document.querySelector("#full_phone").value = iti.getNumber();
  });
});
</script>
@endpush
