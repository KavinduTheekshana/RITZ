@extends('layouts.frontend')

@section('content')
@section('single_page_img', asset('frontend/images/assets/about-banner.webp'))
@section('single_page_name', 'About Us')
@section('single_page_title', 'We’r top rated company ')

@include('frontend.components.inner-banner')
@include('frontend.home.about')
@include('frontend.about.features')
@include('frontend.home.partner')

@endsection
