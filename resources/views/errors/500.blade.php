@extends('frontend.layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center py-20">
    <h1 class="text-8xl font-bold text-yellow-600">500</h1>
    <p class="text-gray-600 text-lg mt-4">Something went wrong on our end. Please try again later.</p>
    <a href="{{ url('/') }}" class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        Back to Homepage
    </a>
</div>
@endsection
