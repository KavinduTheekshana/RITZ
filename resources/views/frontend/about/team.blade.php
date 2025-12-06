		<!--
		=====================================================
			Team Section Three
		=====================================================
		-->
		<div class="team-section-three light-bg position-relative pt-120 lg-pt-60 pb-130 lg-pb-60 mt-90 lg-mt-40">
			<div class="container">
				<div class="position-relative">
					<div class="title-one mb-40 lg-mb-10 wow fadeInUp">
						<h2>Our Advisor</h2>
					</div>
					<!-- /.title-one -->

					<div class="row justify-center">


                        @foreach($team as $member)

						<div class="col-lg-3 col-sm-6 wow fadeInUp cursor-pointer" data-wow-delay="0.3s">
							<div class="card-style-fifteen mt-35">
								<div class="media d-flex align-items-center justify-content-center position-relative overflow-hidden">
									<img src="{{ asset('frontend/images/lazy.svg')}}" data-src="{{ asset('storage/' . $member->image) }}" alt="" class="lazy-img w-100 h-312">
								</div>
								<h4 class="fw-500 pt-20 m0">{{$member->name}}</h4>
								<div class="fs-6">{{$member->designation}}</div>
								@if($member->description)
								<p class="text-muted mt-2 fs-6">{{ $member->description }}</p>
								@endif
							</div>
							<!-- /.card-style-fifteen -->
						</div>
                        @endforeach
					</div>

				</div>
			</div>
		</div>
		<!-- /.team-section-three -->