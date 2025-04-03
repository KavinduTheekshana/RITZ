@extends('layouts.frontend')
{{-- <title>Service Unavailable</title> --}}
@section('content')
<div class="error-page text-center d-flex align-items-center justify-content-center flex-column position-relative
text-feature-two">
    <h1 class="font-magnita text-white">503</h1>
    <h2 class="fw-bold text-white">Service Unavailable</h2>
    <p class="text-lg mb-45 text-white">We're currently undergoing maintenance. Please check back later.</p>
    <div><a href="{{ route('/') }}" class="btn-four">Go Back</a></div>
</div>

@endsection
