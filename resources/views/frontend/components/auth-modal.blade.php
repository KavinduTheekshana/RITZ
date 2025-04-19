<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="container">
            <div class="user-data-form modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="form-wrapper m-auto">
                    <div class="logo order-lg-0">
                        <a href="{{ route('/') }}" class="d-flex align-items-center justify-center">
                            <img src="{{ asset('frontend/images/logo/ritz_dark.svg')}}" class="main-logo" alt="">
                        </a>
                    </div>
                    <div class="tab-content mt-30">
                        <div class="tab-pane show active" role="tabpanel" id="fc1">
                            <div class="text-center mb-20">
                                <h2>Hi, Welcome Back!</h2>
                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-25">
                                            <label>Email*</label>
                                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Youremail@gmail.com" required autofocus autocomplete="username">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-20">
                                            <label>Password*</label>
                                            <input type="password" placeholder="Enter Password" class="pass_log_id" name="password" required autofocus autocomplete="password">
                                            <span class="placeholder_icon"><span class="passVicon"><img src="{{ asset('frontend/images/icon/icon_13.svg')}}" alt=""></span></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="agreement-checkbox d-flex justify-content-between align-items-center">
                                            <div>
                                                <input type="checkbox" name="remember" id="remember">
                                                <label for="remember">Keep me logged in</label>
                                            </div>
                                            <a href="#">Forget Password?</a>
                                        </div> <!-- /.agreement-checkbox -->
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn-four w-100 tran3s d-block mt-20">Login</button>
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


