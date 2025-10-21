@component('mail::message')
{{-- Logo --}}
<p style="text-align: center;">
    <img src="{{ url('images/logo.jpg') }}" style="width: 150px; margin-bottom: 20px;">
</p>

{{-- Greeting --}}
# Hello {{ $user->full_name ?? $user->name }},

We received a request to reset your password.

{{-- Reset Button --}}
@component('mail::button', ['url' => url(route('password.reset', $token, false)), 'color' => 'primary'])
Reset Password
@endcomponent

This password reset link will expire in **60 minutes**.

If you did not request a password reset, no further action is required.

Thanks,<br>
**Mega Market Team**
@endcomponent
