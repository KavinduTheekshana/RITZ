{{-- <div class="block-feature-seventeen position-relative z-1 light-bg-deep mt-150 lg-mt-90 pt-140 lg-pt-80 pb-140 lg-pb-80">
    <div class="container">
        <div class="position-relative">
            <div class="row">
                <div class="col-lg-5">
                    <div class="title-one text-center text-lg-start mb-30 md-mb-10">
                        <h2 class="color-deep">Our Services</h2>
                    </div>

                </div>
                <div class="col-lg-7">
                    <p class="text-lg mb-30">Expert Financial Services Designed to Simplify Your Accounting, Tax, and Payroll Needs</p>
                <a href="service-v1.html" class="btn-three border-style">
                    <span>More Services</span>
                </a>
                </div>
            </div>
            <div class="row justify-content-center mt-100 md-mt-10">

                @foreach ($services as $service)
                <div class="col-lg-4 col-sm-6 d-flex wow fadeInUp">
                    <div class="card-style-twentyTwo w-100 tran3s mt-30">

                        <img src="{{ asset('storage/' . $service->icon) }}" alt="{{ $service->short_title }}" class="lazy-img icon m-auto">
                        <div class="text">
                            <h4 class="fw-bold color-deep">{{ $service->short_title }}</h4>

                            <a href="service-details.html" class="learn-btn tran3s stretched-link">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
 --}}


<div class="block-feature-five light-bg position-relative mt-80 md-mt-50 pt-120 lg-pt-80 pb-150 lg-pb-80">
    <div class="container">
        <div class="position-relative">
            <div class="row">
                <div class="col-lg-8 wow fadeInLeft">
                    <div class="title-one mb-50 lg-mb-30 md-mb-10">
                        <h2>Our Services</h2>
                    </div>
                    <!-- /.title-one -->
                </div>

                <div class="col-lg-4 t-25 section-btn md-mt-40">
                    <a href="service-v2.html" class="btn-seven d-inline-flex align-items-center">
                        <span class="text">All Services</span>
                        <div class="icon tran3s rounded-circle d-flex align-items-center"><img
                                src="{{ asset('frontend/images/lazy.svg') }}"
                                data-src="{{ asset('frontend/images/icon/icon_27.svg') }}" alt=""
                                class="lazy-img"></i></div>
                    </a>
                </div>
            </div>
            <div class="row">
                @foreach ($services as $service)
                    <div class="col-xl-3 col-md-6 d-flex wow fadeInUp">
                        <div class="card-style-seven text-center vstack tran3s w-100 mt-30">
                            <div
                                class="icon tran3s rounded-circle d-flex align-items-center justify-content-center m-auto">
                                <img src="{{ asset('frontend/images/lazy.svg') }}"
                                    data-src="{{ asset('storage/' . $service->icon) }}" alt="" class="lazy-img partner-logo-style-2">
                            </div>
                            <h4 class="fw-bold mt-40 md-mt-20 mb-20">{{ $service->short_title }}</h4>
                            <p class="mb-60 md-mb-40">{{ $service->meta_description }}</p>
                            <a href="service-details.html" class="arrow-btn tran3s m-auto stretched-link"><img
                                    src="{{ asset('frontend/images/lazy.svg') }}"
                                    data-src="{{ asset('frontend/images/icon/icon_09.svg') }}" alt=""
                                    class="lazy-img"></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- /.block-feature-five -->
