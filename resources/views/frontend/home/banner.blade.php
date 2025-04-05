		<!--
		=============================================
			Hero Banner
		==============================================
		-->
		<div class="hero-banner-eight pt-200 lg-pt-150 pb-225 xl-pb-150 lg-pb-150 md-pb-120 sm-pb-20 position-relative z-1">
			<div class="container position-relative">
				<div class="row">
					<div class="col-lg-6 col-md-8">
						<h1 class="hero-heading text-white position-relative wow fadeInUp">Professional, Smart, Reliable, <br> <span id="typed"></span></h1>
						<div class="row">
                            <div class="col-xl-11">
                                <p class="text-xl text-white pt-25 lg-pt-20 pb-40 lg-pb-30 md-pb-20 wow fadeInUp" data-wow-delay="0.1s">Your partner in Business Sustainability.</p>
                            </div>
                        </div>
						<div class="d-lg-inline-flex align-items-center wow fadeInUp" data-wow-delay="0.2s">
							<ul class="style-none d-flex flex-wrap align-items-center">
                                <li class="me-3 mt-10"><a href="{{ route('services') }}" class="btn-twentyOne">Explore Our Services</a></li>
                                <li class="mt-10"><a href="{{ route('contact') }}" class="btn-twentytwo">Contact Us</a></li>
                            </ul>
						</div>
					</div>
				</div>
			</div>
			<div class="media-wrapper wow fadeInUp">
                <img src="{{ asset('frontend/images/lazy.svg')}}" data-src="{{ asset('frontend/images/assets/businessman_04.png')}}" alt="" class="lazy-img w-100">
            </div>
		</div>
		<!-- /.hero-banner-eight -->

		@push('scripts')
		<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
		<script>
			var typed = new Typed("#typed", {
				strings: ["Accounting.", "Bookkeeping.", "Payroll.", "Taxation.", "Advisory."], // Words to cycle
				typeSpeed: 100, // Typing speed
				backSpeed: 50,  // Erase speed
				startDelay: 500, // Delay before typing starts
				backDelay: 1500, // Delay before erasing
				showCursor: true, // Show blinking cursor
				loop: true // Infinite loop
			});
		</script>
		@endpush