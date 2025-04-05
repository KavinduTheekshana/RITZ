@extends('layouts.frontend')

@section('title', "Services | Ritz Accounting & Advisory")
@section('meta_description', 'From company formation to tax compliance, our UK-based accountants help businesses stay financially organized and HMRC ready.')

@section('content')

@section('single_page_img', asset('frontend/images/assets/services.webp'))
@section('single_page_name', 'Services')
@section('single_page_title', 'Explore Our Expertise')
@include('frontend.components.inner-banner')
@include('frontend.services.offerings')
@include('frontend.services.services')
@include('frontend.home.testimonial')
@include('frontend.services.pricing')
@include('frontend.about.banner-buttom')

@endsection
