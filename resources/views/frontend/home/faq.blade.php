<div class="faq-section-four light-bg-deep  lg-mt-80 pt-120 lg-pt-80 pb-120 lg-pb-80 mb-100 lg-mb-50">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 m-auto">
                <div class="title-one text-center mb-30 lg-mb-20">
                    <h2 class="color-deep">Frequently asked & Question</h2>
                </div>
                <!-- /.title-one -->
            </div>
        </div>

        <p class="text-lg pb-60 lg-pb-40 color-deep text-center">We're here to ensure you have the information you need for a seamless experience.</p>

        <div class="row">
            <div class="col-xxl-10 m-auto">
                <div class="accordion accordion-style-four" id="accordionOne">

                    <div class="accordion" id="accordionOne">
                        @foreach($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                        <span>{{ sprintf('%02d', $index + 1) }}.</span> {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionOne">
                                    <div class="accordion-body">
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>