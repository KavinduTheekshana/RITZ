@extends('layouts.frontend')

@section('content')
<div class="error-page text-center d-flex align-items-center justify-content-center flex-column position-relative
text-feature-two">
    <h1 class="font-magnita text-white">404</h1>
    <h2 class="fw-bold text-white">Page Not Found</h2>
    <p class="text-lg mb-45 text-white">Oops! The page you are looking for does not exist.</p>
    <div><a href="{{ route('/') }}" class="btn-four">Go Back</a></div>
</div>

@endsection
