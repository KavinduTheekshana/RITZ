@extends('layouts.frontend')

@section('title', "Blog | Credipath Accountancy & Advisory Ltd")
@section('meta_description', 'Streamlined accounting and advisory solutions for UK companies. Get professional help with taxes, payroll, VAT, and more.')

@section('content')

@section('single_page_img', asset('frontend/images/assets/blog.webp'))
@section('single_page_name', 'Blog')
@section('single_page_title', 'Latest Articles & News')
@include('frontend.components.inner-banner')
@include('frontend.blog.articles')
@include('frontend.about.banner-buttom')


@endsection
