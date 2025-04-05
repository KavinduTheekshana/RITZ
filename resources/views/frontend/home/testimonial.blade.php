<div class="feedback-section-seven mt-120 lg-mt-80">
    <div class="box-layout position-relative z-1">
        <div class="container">
            <div class="wrapper">
                <div class="title-two text-center mb-45 sm-mb-30">
                    <div class="upper-title-two">Words from
                        clients.</div>
                </div>
                <!-- /.title-two -->
                <div class="feedback-slider-six">

                    @foreach ($testimonials as $testimonial)
                        <div class="item">
                            <div class="feedback-block-seven">
                                <div class="row">
                                    <div class="col-lg-9 col-md-10 m-auto">
                                        <blockquote>{{ $testimonial->message }}</blockquote>
                                        <div class="mt-50 lg-mt-30">
                                            <div class="name fw-500 text-dark">{{ $testimonial->name }}</div>
                                            <p class="fs-5 m0">{{ $testimonial->designation }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
        <ul class="slider-arrows slick-arrow-one d-flex justify-content-between style-none">
            <li class="prev_S tran3s slick-arrow d-flex align-items-center justify-content-center"><img
                    src="{{ asset('frontend/images/lazy.svg') }}"
                    data-src="{{ asset('frontend/images/icon/icon_103.svg') }}" alt="" class="lazy-img"></li>
            <li class="next_S tran3s slick-arrow d-flex align-items-center justify-content-center"><img
                    src="{{ asset('frontend/images/lazy.svg') }}"
                    data-src="{{ asset('frontend/images/icon/icon_102.svg') }}" alt="" class="lazy-img"></li>
        </ul>
    </div>
</div>
