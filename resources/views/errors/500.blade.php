@extends('layouts.frontend')
{{-- <title>Internal Server Error</title> --}}
@section('content')
<div class="error-page text-center d-flex align-items-center justify-content-center flex-column position-relative
text-feature-two">
    <h1 class="font-magnita text-white">500</h1>
    <h2 class="fw-bold text-white">Internal Server Error</h2>
    <p class="text-lg mb-45 text-white">Something went wrong on our server. Please try again later.</p>
    <div><a href="{{ route('/') }}" class="btn-four">Go Back</a></div>
</div>

@endsection
