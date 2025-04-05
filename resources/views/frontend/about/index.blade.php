@extends('layouts.frontend')

@section('title', "About Us | Ritz Accounting & Advisory")
@section('meta_description', 'Expert tax return filing, VAT returns, bookkeeping, and payroll services. Supporting UK businesses with reliable, low-cost accounting.')

@section('content')

@section('single_page_img', asset('frontend/images/assets/about-banner.webp'))
@section('single_page_name', 'About Us')
@section('single_page_title', 'We’r top rated company ')

@include('frontend.components.inner-banner')
@include('frontend.home.about')
@include('frontend.about.features')
@include('frontend.about.vision')

@include('frontend.about.team')
@include('frontend.home.partner')
@include('frontend.about.banner-buttom')

@endsection
