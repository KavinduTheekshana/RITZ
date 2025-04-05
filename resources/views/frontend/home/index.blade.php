@extends('layouts.frontend')

@section('content')
    @include('frontend.home.banner')
    @include('frontend.home.about')
    @include('frontend.home.features')

    @include('frontend.home.services')
    @include('frontend.home.partner')
    @include('frontend.home.how-ritz-works')
    @include('frontend.home.testimonial')
    @include('frontend.home.faq')
@endsection
