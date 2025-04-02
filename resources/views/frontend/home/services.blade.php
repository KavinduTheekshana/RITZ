<div class="block-feature-seventeen position-relative z-1 light-bg-deep mt-150 lg-mt-90 pt-140 lg-pt-80 pb-140 lg-pb-80">
    <div class="container">
        <div class="position-relative">
            <div class="row">
                <div class="col-lg-5">
                    <div class="title-one text-center text-lg-start mb-30 md-mb-10">
                        <h2 class="color-deep">Our Services</h2>
                    </div>
                    <!-- /.title-one -->
                </div>
                <div class="col-lg-7">
                    <p class="text-lg mb-30">Expert Financial Services Designed to Simplify Your Accounting, Tax, and Payroll Needs</p>
                <a href="service-v1.html" class="btn-three border-style">
                    <span>More Services</span>
                </a>
                </div>
            </div>
            <div class="row justify-content-center mt-100 md-mt-10">

                @foreach($services as $service)
                <div class="col-lg-4 col-sm-6 d-flex wow fadeInUp">
                    <div class="card-style-twentyTwo w-100 tran3s mt-30">
                        {{-- <div class="service-icon lazy-img icon m-auto">{!! $service->icon !!}</div> --}}
                        <img src="{{ asset('storage/' . $service->icon) }}" alt="{{ $service->short_title }}" class="lazy-img icon m-auto">
                        <div class="text">
                            <h4 class="fw-bold color-deep">{{ $service->short_title }}</h4>
                            {{-- <p>{{ $service->meta_description }}</p> --}}
                            <a href="service-details.html" class="learn-btn tran3s stretched-link">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            {{-- <div class="section-subheading md-mt-40">
                <p class="text-lg mb-30">Inciddnt ut labore et dolor magna aliu.ad mim venam, quis nostru </p>
                <a href="service-v1.html" class="btn-three border-style">
                    <span>More Services</span>
                </a>
            </div> --}}
            <!-- /.section-subheading -->
        </div>
        {{-- <img src="images/lazy.svg" data-src="images/shape/shape_55.png" alt="" class="lazy-img shapes shape_01"> --}}
    </div>
</div>
<!-- /.block-feature-seventeen -->
