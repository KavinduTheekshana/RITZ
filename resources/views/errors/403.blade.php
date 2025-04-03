@extends('layouts.frontend')
{{-- <title>Access Denied</title> --}}
@section('content')
<div class="error-page text-center d-flex align-items-center justify-content-center flex-column position-relative
text-feature-two">
    <h1 class="font-magnita text-white">403</h1>
    <h2 class="fw-bold text-white">Access Denied</h2>
    <p class="text-lg mb-45 text-white">You don't have permission to access this page.</p>
    <div><a href="{{ route('/') }}" class="btn-four">Go Back</a></div>
</div>

@endsection
