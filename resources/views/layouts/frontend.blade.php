<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ritz Accounting & Advisory | Your partner in Business Sustainability.')</title>
    <meta name="description" content="@yield('meta_description', 'RITZ Accounting offers simple, stress-free accounting services tailored to your business needs. Trusted by businesses across the UK.')">
    <meta name="keywords" content="@yield('meta_keywords', 'accounting services UK, affordable accountants, HMRC compliance, bookkeeping services, VAT return filing, payroll management, tax return help UK, online accountants, small business accounting, self-assessment tax return, company formation services, business advisory UK')">

    <meta name="title" content="@yield('title', 'Ritz Accounting & Advisory | Your partner in Business Sustainability.')">
    <link rel="canonical" href="https://ritzaccounting.co.uk" />
    <meta name="author" content="www.ritzaccounting.co.uk">

    <meta property="og:title" content="@yield('title', 'Ritz Accounting & Advisory | Your partner in Business Sustainability.')" />
    <meta property="og:description" content="@yield('meta_description', 'RITZ Accounting offers simple, stress-free accounting services tailored to your business needs. Trusted by businesses across the UK.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ritzaccounting.co.uk">
    <meta property="og:image" content="@yield('ogimage', asset('frontend/assets/images/logo/og.jpg'))">
    <meta property="og:site_name" content="Ritz Accounting & Advisory">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Ritz Accounting & Advisory | Your partner in Business Sustainability.')">
    <meta name="twitter:description" content="@yield('meta_description', 'RITZ Accounting offers simple, stress-free accounting services tailored to your business needs. Trusted by businesses across the UK.')">
    <meta name="twitter:image" content="@yield('ogimage', asset('frontend/images/logo/og.webp'))">
    <!-- For IE -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- For Resposive Device -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- For Window Tab Color -->
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#1A4137">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#1A4137">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#1A4137">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('frontend/images/logo/icon.svg') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css') }}" media="all">
    <!-- Main style sheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.min.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/custom.css') }}" media="all">
    <!-- responsive style sheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/responsive.css') }}" media="all">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
    {{-- @vite(['', 'resources/js/app.js']) --}}

    <!-- Fix Internet Explorer ______________________________________-->
    <!--[if lt IE 9]>
   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
   <script src="vendor/html5shiv.js"></script>
   <script src="vendor/respond.js"></script>
  <![endif]-->
</head>

<body>
    <div class="main-page-wrapper">
        <!-- ===================================================
    Loading Transition
   ==================================================== -->
        <div id="preloader">
            <div id="ctn-preloader" class="ctn-preloader">
                <div class="icon"><img src="{{ asset('frontend/images/logo/icon.svg') }}" alt=""
                        class="m-auto d-block" width="160">
                </div>
                <div class="txt-loading">
                    <span data-text-preloader="R" class="letters-loading">
                        R
                    </span>
                    <span data-text-preloader="I" class="letters-loading">
                        I
                    </span>
                    <span data-text-preloader="T" class="letters-loading">
                        T
                    </span>
                    <span data-text-preloader="Z" class="letters-loading">
                        Z
                    </span>

                </div>
            </div>
        </div>
        @if (!Request::is('login'))
        @include('frontend.components.header')
        @endif

        @yield('content')
        @if (!Request::is('login'))
        @include('frontend.components.footer')
        @include('frontend.components.auth-modal')
        @include('frontend.components.password-reset')
        @endif

        <button class="scroll-top">
            <i class="bi bi-arrow-up-short"></i>
        </button>




        <!-- jQuery first, then Bootstrap JS -->
        <!-- jQuery -->
        <script src="{{ asset('frontend/vendor/jquery.min.js') }}"></script>
        <!-- Bootstrap JS -->
        <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- WOW js -->
        <script src="{{ asset('frontend/vendor/wow/wow.min.js') }}"></script>
        <!-- Slick Slider -->
        <script src="{{ asset('frontend/vendor/slick/slick.min.js') }}"></script>
        <!-- Fancybox -->
        <script src="{{ asset('frontend/vendor/fancybox/dist/jquery.fancybox.min.js') }}"></script>
        <!-- Lazy -->
        <script src="{{ asset('frontend/vendor/jquery.lazy.min.js') }}"></script>
        <!-- js Counter -->
        <script src="{{ asset('frontend/vendor/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('frontend/vendor/jquery.waypoints.min.js') }}"></script>

        <!-- validator js -->
        <script src="{{ asset('frontend/vendor/validator.js') }}"></script>

        <!-- Theme js -->
        <script src="{{ asset('frontend/js/theme.js') }}"></script>

      

        @stack('scripts')

   
    </div> <!-- /.main-page-wrapper -->

</body>

</html>