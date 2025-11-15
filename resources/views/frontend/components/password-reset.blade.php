<!-- Password Reset Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
      <div class="container">
        <div class="user-data-form modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="form-wrapper m-auto">
            <div class="logo order-lg-0">
              <a href="{{ route('/') }}" class="d-flex align-items-center justify-center">
                <img src="{{ asset('frontend/images/logo/Credipath_Dark.svg')}}" class="main-logo" alt="">
              </a>
            </div>
            <div class="tab-content mt-30">
              <div class="tab-pane show active" role="tabpanel">
                <div class="text-center mb-20">
                  <h2>Reset Your Password</h2>
                  <p class="mt-2 text-dark line-height-1">Enter your email address and we will send you a link to reset your password.</p>
                </div>
                <form class="mt-42">
                  @csrf
                  <div class="row">
                    <div class="col-12">
                      <div class="input-group-meta position-relative mb-25">
                        <label>Email*</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Youremail@gmail.com" required autofocus>
                      </div>
                    </div>
                    <div class="col-12">
                      <button type="submit" class="btn-four w-100 tran3s d-block mt-20">Send Reset Link</button>
                    </div>
                    <div class="col-12 text-center mt-3">
                      <a href="#" class="back-to-login text-gray-link" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Back to Login</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /.form-wrapper -->
        </div>
        <!-- /.user-data-form -->
      </div>
    </div>
  </div>