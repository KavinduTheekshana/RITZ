@extends('layouts.frontend')

@section('title', "$service->title | Ritz Accounting & Advisory")
@section('meta_description', $service->meta_description)
@section('meta_keywords', $service->meta_keywords)

@section('content')

@section('single_page_img', asset('frontend/images/assets/services.webp'))
@section('single_page_name', $service->short_title)
@section('single_page_title', $service->title)
@include('frontend.components.inner-banner')


<div class="service-details mt-150 lg-mt-80 mb-100 lg-mb-80">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8 order-lg-last">
                <div class="details-meta ps-xxl-5 ps-xl-3">
                    <h2 class="m-0">{{ $service->title }}</h2>
                    <small>{{ $service->sub_title }}</small>
                    <br>
                    <p class="mt-42">{!!$service->description!!}</p>



                </div>
            </div>
            <div class="col-xxl-3 col-lg-4 order-lg-first">
                <aside class="md-mt-40">
                    <div class="service-nav-item">
                        <ul class="style-none">

                            @foreach ($allServices as $serviceItem)
                                <li>
                                    <a href="{{ route('service.show', $serviceItem->slug) }}"
                                        class="d-flex align-items-center w-100 {{ $serviceItem->id === $service->id ? 'active-service' : '' }}">
                                        <img src="{{ asset('frontend/images/lazy.svg') }}" style="opacity: 1;"
                                            data-src="{{ asset('storage/' . $serviceItem->icon) }}"
                                            alt="{{ $serviceItem->short_title ?? $serviceItem->title }}"
                                            class="lazy-img icon-service {{ $serviceItem->id === $service->id ? 'active-service-icon' : '' }}">
                                        <span>{{ $serviceItem->short_title ?? $serviceItem->title }}</span>
                                    </a>
                                </li>
                            @endforeach




                        </ul>
                    </div>
                    <div class="contact-banner text-center mt-40 lg-mt-20">
                        <h3 class="mb-20">Any Questions? Let’s talk</h3>
                        <a href="{{ route('contact') }}" class="tran3s fw-500">Let’s Talk</a>
                    </div>
                    <!-- /.contact-banner -->
                </aside>
            </div>
        </div>
    </div>
</div>
@include('frontend.about.banner-buttom')

@endsection
