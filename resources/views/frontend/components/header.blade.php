		<!--
		=============================================
				Theme Main Menu
		==============================================
		-->
		<header class="theme-main-menu menu-overlay menu-style-four white-vr sticky-menu">
			<div class="inner-content position-relative">
				<div class="top-header">
					<div class="d-flex align-items-center justify-content-between">
						<div class="logo order-lg-0">
							<a href="index.html" class="d-flex align-items-center">
								<img src="{{ asset('frontend/images/logo/ritz.svg')}}" class="main-logo" alt="">
							</a>
						</div>
						<!-- logo -->
						{{-- <div class="right-widget d-none d-md-block ms-auto ms-lg-0 me-3 me-lg-0 order-lg-3">
							<a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="quote-one fw-500 tran3s">Login / Register</a>
						</div> --}}

						<nav class="navbar navbar-expand-lg p0 order-lg-2">
							<button class="navbar-toggler d-block d-lg-none" type="button" data-bs-toggle="collapse"
								data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
								aria-label="Toggle navigation">
								<span></span>
							</button>
							<div class="collapse navbar-collapse" id="navbarNav">
								<ul class="navbar-nav align-items-lg-center">
									<li class="d-block d-lg-none"><div class="logo"><a href="index.html" class="d-block mobile-logo ml-0"><img src="{{ asset('frontend/images/logo/ritz.svg')}}" alt=""></a></div></li>

									<li class="nav-item">
										<a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('/') }}" role="button">Home</a>
									</li>

									<li class="nav-item">
										<a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}" role="button">About Us</a>
									</li>

									<li class="nav-item">
										<a class="nav-link {{ request()->is('services') ? 'active' : '' }}" href="{{ route('services') }}" role="button">Services</a>
									</li>

									<li class="nav-item">
										<a class="nav-link {{ request()->is('blog') ? 'active' : '' }}" href="{{ route('blog') }}" role="button">Blog</a>
									</li>


									<li class="nav-item">
										<a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}" role="button">Contact Us</a>
									</li>
									{{-- <li class="d-md-none ps-2 pe-2 mt-15"><a href="contact.html" class="btn-one text-center w-100 fw-500 tran3s">Login / Register</a></li> --}}
								</ul>
							</div>
						</nav>
					</div>
				</div> <!--/.top-header-->
			</div> <!-- /.inner-content -->
		</header>
		<!-- /.theme-main-menu -->