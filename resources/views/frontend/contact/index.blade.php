@extends('layouts.frontend')

@section('title', "Contact Us | Ritz Accounting & Advisory")
@section('meta_description', 'Affordable accounting services in the UK for small businesses, startups, and self-employed individuals. HMRC-compliant solutions you can trust.')

@section('content')

@section('single_page_img', asset('frontend/images/assets/contact.webp'))
@section('single_page_name', 'Contact Us')
@section('single_page_title', 'Contact us for inquiries')
@include('frontend.components.inner-banner')

<div class="contact-us-section pt-150 lg-pt-80">
    <div class="container">
        <div class="position-relative">
            <div class="row">
                <div class="col-12 m-auto">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="address-block-one text-center mb-40 wow fadeInUp">
                                <div class="icon rounded-circle d-flex align-items-center justify-content-center m-auto">
                                    <img src="{{ asset('frontend/images/lazy.svg') }}"
                                        data-src="{{ asset('frontend/images/icon/icon_90.svg') }}" alt=""
                                        class="lazy-img">
                                </div>
                                <h5 class="title">Our Address</h5>
                                <p>41 Hubbards Close<br> Uxbridge, UB8 3HB</p>
                            </div> <!-- /.address-block-one -->
                        </div>
                        <div class="col-md-4">
                            <div class="address-block-one text-center mb-40 wow fadeInUp">
                                <div
                                    class="icon rounded-circle d-flex align-items-center justify-content-center m-auto">
                                    <img src="{{ asset('frontend/images/lazy.svg') }}"
                                        data-src="{{ asset('frontend/images/icon/icon_91.svg') }}" alt=""
                                        class="lazy-img">
                                </div>
                                <h5 class="title">Contact Number</h5>
                                <p><a href="tel:0800 056 3641" class="call text-lg fw-500">0800 056 3641</a></p>
                            </div> <!-- /.address-block-one -->
                        </div>
                        <div class="col-md-4">
                            <div class="address-block-one text-center mb-40 wow fadeInUp">
                                <div
                                    class="icon rounded-circle d-flex align-items-center justify-content-center m-auto">
                                    <img src="{{ asset('frontend/images/lazy.svg') }}"
                                        data-src="{{ asset('frontend/images/icon/icon_92.svg') }}" alt=""
                                        class="lazy-img">
                                </div>
                                <h5 class="title">Email Address</h5>
                                <p><a href="mailto:info@ritzaccounting.co.uk"
                                        class="webaddress-contact">info@ritzaccounting.co.uk</a>
                                    <a href="mailto:sales@ritzaccounting.co.uk"
                                        class="webaddress-contact">sales@ritzaccounting.co.uk</a>
                                </p>
                            </div> <!-- /.address-block-one -->
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="bg-wrapper light-bg mt-80 lg-mt-40"> --}}
            @include('frontend.contact.form')
            {{-- </div> --}}
        </div>
    </div>
    @include('frontend.contact.map')
</div>
@endsection
