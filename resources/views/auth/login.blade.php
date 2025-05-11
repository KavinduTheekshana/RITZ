@push('styles')
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .login-container {
            height: 100vh;
            display: flex;
            flex-wrap: wrap;
        }

        .left-side {
            background-image: url('https://picsum.photos/1920/1080');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 50%;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .left-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.1) 100%);
        }

        .right-side {
            width: 50%;
            min-height: 100vh;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }







        .agreement-checkbox {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .agreement-checkbox input[type="checkbox"] {
            margin-right: 8px;
        }

        .agreement-checkbox a {
            color: #5c6ac4;
            text-decoration: none;
            font-size: 14px;
        }

        .main-logo {
            width: 40vh !important;
        }







        /* Responsive Design */
        @media (max-width: 992px) {
            .left-side {
                width: 40%;
            }

            .right-side {
                width: 60%;
            }
        }

        @media (max-width: 768px) {
            .left-side {
                display: none;
            }

            .right-side {
                width: 100%;
                padding: 20px;
            }

            .user-data-form {
                max-width: 400px;
            }
        }
    </style>
@endpush
@extends('layouts.frontend')

@section('content')
    <div class="login-container">
        <!-- Left Side Image -->
        <div class="left-side">
            <!-- You can add any overlay content here if needed -->
        </div>

        <!-- Right Side Form -->
        <div class="right-side">
            <div class="user-data-form">

                <div class="form-wrapper">
                    <div class="logo">
                        <a href="{{ route('/') }}" class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('frontend/images/logo/ritz_dark.svg') }}" class="main-logo" alt="Logo">
                        </a>
                    </div>
                    <div class="tab-content mt-4">
                        <div class="tab-pane show active" role="tabpanel" id="fc1">
                            <div class="text-center mb-4">
                                <h2>Hi, Welcome Back!</h2>
                            </div>
                            <form method="POST" action="{{ route('client.login.submit') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-3">
                                            <label>Email*</label>
                                            <input type="email" name="email" placeholder="Youremail@gmail.com"
                                                class="@error('email') is-invalid @enderror" value="{{ old('email') }}"
                                                required autofocus autocomplete="username">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group-meta position-relative mb-3">
                                            <label>Password*</label>
                                            <input type="password" placeholder="Enter Password"
                                                class="pass_log_id @error('password') is-invalid @enderror" name="password"
                                                required autofocus autocomplete="password">
                                            <span class="placeholder_icon">
                                                <span class="passVicon">
                                                    <img src="{{ asset('frontend/images/icon/icon_13.svg') }}"
                                                        alt="">
                                                </span>
                                            </span>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="agreement-checkbox">
                                            <div>
                                                <input type="checkbox" name="remember" id="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <label for="remember">Keep me logged in</label>
                                            </div>
                                            <a href="#">Forget Password?</a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn-four w-100 tran3s d-block mt-3">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
