  <!--
  =====================================================
   Footer Two
  =====================================================
  -->
  <div class="footer-two no-bg">
      <div class="container">
          <div class="bg-wrapper position-relative">
              <div class="container">
                  <div class="row justify-content-between">
                      <div class="col-xl-3 col-lg-4 footer-intro mb-30">
                          <div class="logo mb-35 md-mb-20">
                              <a href="{{ route('/') }}">
                                  <img src="{{ asset('frontend/images/logo/ritz_dark.svg') }}" class="main-logo"
                                      alt="">
                              </a>
                          </div>
                          <!-- logo -->
                          <p class="lh-sm mb-40 md-mb-20">Providing trusted accounting services for businesses across
                              the UK.</p>

                          <ul class="style-none d-flex align-items-center social-icon">
                              <li><a target="_blank" href="https://www.facebook.com/profile.php?id=61574686905951"><i
                                          class="bi bi-facebook"></i></a></li>
                              <li><a target="_blank" href="https://www.instagram.com/ritz_acct/"><i
                                          class="bi bi-instagram"></i></a></li>
                          </ul>



                      </div>
                      <div class="col-lg-2 col-sm-4 mb-20">
                          <h5 class="footer-title">Links</h5>
                          <ul class="footer-nav-link style-none">
                              <li><a href="{{ route('/') }}">Home</a></li>
                              <li><a href="{{ route('about') }}">About us</a></li>
                              <li><a href="{{ route('services') }}">Services</a></li>
                              <li><a href="{{ route('blog') }}">Blog</a></li>
                              <li><a href="{{ route('contact') }}">Contact us</a></li>
                          </ul>
                      </div>
                      <div class="col-lg-2 col-sm-4 mb-20">
                          <h5 class="footer-title">Services</h5>
                          <ul class="footer-nav-link style-none">
                              @php
                                  // Get latest 5 services
                                  // Replace 'App\Models\Service' with your actual service model
                                  $services = App\Models\Service::where('status', true)->latest()->take(5)->get();
                              @endphp

                              @forelse($services as $service)
                                  <li><a href="{{ route('service.show', $service->slug) }}">{{ $service->short_title }}</a></li>
                                  {{-- <li><a href="#">{{ $service->short_title }}</a></li> --}}
                              @empty
                                  <li><a href="#">Financial Services</a></li>
                                  <li><a href="#">Loan Services</a></li>
                                  <li><a href="#">Banking Services</a></li>
                                  <li><a href="#">Investment Planning</a></li>
                                  <li><a href="#">Insurance Services</a></li>
                              @endforelse
                          </ul>
                      </div>
                      <div class="col-xxl-2 col-lg-3 col-sm-4 mb-20">
                          <h5 class="footer-title">Support</h5>
                          <ul class="footer-nav-link style-none">
                              <li><a href="#">Careers</a></li>
                              <li><a href="#">Terms & conditions</a></li>
                              <li><a href="#">Privacy</a></li>
                              <li><a href="#">Cookie policy</a></li>
                          </ul>
                      </div>
                  </div>
                  <hr>
                  <div class="copyright text-center position-initial mt-20">Copyright {{ date('Y') }} RITZ
                      Accountants |
                      Developed by <a href="https://creatxsoftware.com" target="_blank" class="creatx">Creatx Software
                          Ltd</a>
                  </div>
              </div>
          </div>
          <!-- /.bg-wrapper -->
      </div>
  </div> <!-- /.footer-two -->
