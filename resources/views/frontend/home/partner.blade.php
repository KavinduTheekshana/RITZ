<div class="feedback-section-three position-relative lg-pt-100 mt-150 lg-mt-80 lg-pb-100 mb-150 lg-mb-80">
    <div class="container">
        <div class="position-relative">

            <div class="partner-logo-one">
{{--
                <div class="row mb-40">
                    <div class="col-lg-5">
                        <div class="title-one text-center text-lg-start mb-30 md-mb-10">
                            <h2 class="color-deep">Leading Partners</h2>
                        </div>
                        <!-- /.title-one -->
                    </div>
                    <div class="col-lg-7">
                        <p class="text-lg mb-30">We collaborate with businesses to deliver innovative solutions, ensuring
                            growth, efficiency, and long term success. With expertise and dedication, we help you stay
                            ahead in the industry.</p>

                    </div>
                </div> --}}

                <div class="partner-slider-one">
                    @foreach($partners as $partner)
                    <div class="item">
                        <div class="logo d-flex align-items-center justify-content-center justify-content-lg-start partner-logo"><img
                                src="{{ asset('storage/' . $partner->logo) }}" alt=""></div>
                    </div>
                    @endforeach

                </div>
            </div>
            <!-- /.partner-logo-one -->
        </div>
    </div>


</div>
