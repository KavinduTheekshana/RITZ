<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Credipath Accountancy & Advisory Ltd | Your partner in Business Sustainability.')</title>
    <meta name="description" content="@yield('meta_description', 'Credipath Accountancy & Advisory Ltd offers simple, stress-free accounting services tailored to your business needs. Trusted by businesses across the UK.')">
    <meta name="keywords" content="@yield('meta_keywords', 'accounting services UK, affordable accountants, HMRC compliance, bookkeeping services, VAT return filing, payroll management, tax return help UK, online accountants, small business accounting, self-assessment tax return, company formation services, business advisory UK')">

    <meta name="title" content="@yield('title', 'Credipath Accountancy & Advisory Ltd | Your partner in Business Sustainability.')">
    <link rel="canonical" href="https://credipath.co.uk" />
    <meta name="author" content="www.credipath.co.uk">

    <meta property="og:title" content="@yield('title', 'Credipath Accountancy & Advisory Ltd | Your partner in Business Sustainability.')" />
    <meta property="og:description" content="@yield('meta_description', 'Credipath Accountancy & Advisory Ltd offers simple, stress-free accounting services tailored to your business needs. Trusted by businesses across the UK.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://credipath.co.uk">
    <meta property="og:image" content="@yield('ogimage', asset('frontend/assets/images/logo/og.jpg'))">
    <meta property="og:site_name" content="Credipath Accountancy & Advisory Ltd">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Credipath Accountancy & Advisory Ltd | Your partner in Business Sustainability.')">
    <meta name="twitter:description" content="@yield('meta_description', 'Credipath Accountancy & Advisory Ltd offers simple, stress-free accounting services tailored to your business needs. Trusted by businesses across the UK.')">
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
    <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('frontend/images/logo/Credipath_icon.svg') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css') }}" media="all">
    <!-- Main style sheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.min.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/custom.css') }}" media="all">
    <!-- responsive style sheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/responsive.css') }}" media="all">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Credipath Loading Animation Styles -->
    <style>
        #preloader {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 999999;
            width: 100%;
            height: 100%;
            overflow: visible;
            background: linear-gradient(135deg, #1d6265 0%, #0a3638 100%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .credipath-loading-container {
            text-align: center;
            position: relative;
        }

        .credipath-logo-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        /* Main rotating circle background */
        .credipath-circle-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 220px;
            height: 220px;
            border: 3px solid transparent;
            border-top-color: #c4d92d;
            border-right-color: #c4d92d;
            border-radius: 50%;
            animation: credipath-rotate 1.5s linear infinite;
        }

        /* Secondary rotating circle */
        .credipath-circle-bg-secondary {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 240px;
            height: 240px;
            border: 2px solid transparent;
            border-bottom-color: rgba(196, 217, 45, 0.3);
            border-left-color: rgba(196, 217, 45, 0.3);
            border-radius: 50%;
            animation: credipath-rotate-reverse 2s linear infinite;
        }

        /* Logo container with pulse */
        .credipath-logo-container {
            position: relative;
            width: 200px;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: credipath-pulse 2s ease-in-out infinite;
        }

        .credipath-logo-container svg {
            width: 140px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        /* Animated path drawing for the chart line */
        #credipath-chart-path {
            stroke-dasharray: 500;
            stroke-dashoffset: 500;
            animation: credipath-drawPath 2s ease-in-out infinite;
        }

        /* Pulsing dots around the logo */
        .credipath-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #c4d92d;
            border-radius: 50%;
            opacity: 0;
            animation: credipath-dotPulse 2s ease-in-out infinite;
        }

        .credipath-dot:nth-child(1) {
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            animation-delay: 0s;
        }

        .credipath-dot:nth-child(2) {
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            animation-delay: 0.5s;
        }

        .credipath-dot:nth-child(3) {
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            animation-delay: 1s;
        }

        .credipath-dot:nth-child(4) {
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            animation-delay: 1.5s;
        }

        /* Loading text */
        .credipath-loading-text {
            margin-top: 40px;
            color: #c4d92d;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .credipath-loading-text::after {
            content: '';
            animation: credipath-dots 1.5s steps(4, end) infinite;
        }

        /* Progress bar */
        .credipath-progress-bar {
            width: 200px;
            height: 3px;
            background: rgba(196, 217, 45, 0.2);
            margin: 20px auto 0;
            border-radius: 2px;
            overflow: hidden;
        }

        .credipath-progress-fill {
            height: 100%;
            background: #c4d92d;
            width: 0%;
            animation: credipath-progress 2s ease-in-out infinite;
            box-shadow: 0 0 10px #c4d92d;
        }

        /* Animations */
        @keyframes credipath-rotate {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        @keyframes credipath-rotate-reverse {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(-360deg); }
        }

        @keyframes credipath-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes credipath-dotPulse {
            0%, 100% { opacity: 0; transform: scale(0.5); }
            50% { opacity: 1; transform: scale(1); }
        }

        @keyframes credipath-dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        @keyframes credipath-progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }

        @keyframes credipath-drawPath {
            0% { stroke-dashoffset: 500; }
            50% { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: -500; }
        }

        /* Fade out animation */
        #preloader.hide-loader {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
    
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
    Loading Transition - Credipath Animation
   ==================================================== -->
        <div id="preloader">
            <div class="credipath-loading-container">
                <div class="credipath-logo-wrapper">
                    <div class="credipath-circle-bg"></div>
                    <div class="credipath-circle-bg-secondary"></div>
                    <div class="credipath-dot"></div>
                    <div class="credipath-dot"></div>
                    <div class="credipath-dot"></div>
                    <div class="credipath-dot"></div>
                    
                    <div class="credipath-logo-container">
                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 689.6 646.9">
                            <defs>
                                <style>
                                    .st0 { fill: #1d6265; }
                                    .st1 { fill: #c4d92d; }
                                </style>
                            </defs>
                            <g>
                                <path class="st1" d="M405.5,547.3c19.2-4.4,37-12.4,54-21.9,8.1-4.5,16.9-8.4,23.3-15.3v-231.9c0-3.8-70.3,1.4-76.5-.8-1.6-.2-.7,1.9-.9,2.9v266.9h.1Z"/>
                                <path class="st1" d="M204.9,417.4c-.2,0-2.2,2-2.2,2.2v91.9c8.6,5.3,16.9,11.4,26.3,15.3,2.1,3.9,8,4.2,11.7,5.8,12.8,5.7,25.5,11.9,39.4,14.6v-127.6c0-.2-2-2.2-2.2-2.2h-73,0Z"/>
                                <path class="st1" d="M304.8,553.1c25.4,2.9,50.5,3,75.9,0v-202.9h-75.9c.2,55.9-.2,111.9,0,167.7,0,8.7-.3,17.6,0,26.3.1,2.9,0,5.9,0,8.8h0Z"/>
                                <path class="st0" d="M652.1,617.3c-1.2-1.6-3-1.1-4.4-1.5-3.9-1.2-7.9-1.7-11.7-2.9-6.9-2.1-13.8-5.5-20.8-7.7-33.4-10.3-67-20-100.3-30.3,26.7-18.6,49.3-39.3,70-64.2,10.3-12.4,16.4-21.3,24.8-35,2.7-4.3,6.4-8.8,9-13.6,25-45.7,39.1-98.9,37.7-151.2h-49.6c-.5,52.9-16.6,106.8-46.7,150.2-19.1,27.6-50.2,56.8-78.8,74.4-154.3,94.8-350.4,10.3-395.4-161.9-1.4-5.4-3-12.4-4-19.1,0-.9,0-1.8-.4-2.8-11-83.6,6.2-139.2,49.6-208.6,10.5-11.1,19.5-23.7,30.6-34.3,12.7-12,27.9-21.1,40.9-32.8,10-3.2,20.4-11.3,30.1-15.8,30.3-14.2,55.9-18.5,88.1-25,41.5-3.1,84.6.7,124,14.6,30.5,10.8,64.5,31.4,89,52.5,7.9,6.8,19.5,17.2,26.3,24.8,17.1,19.3,37.8,51.9,48.2,75.9,7.7,17.8,12.9,36.7,17.5,55.4l5.8,33.5c1.4-13.5-2-26.3-4.4-39.4-3.6-19.6-7.6-33.9-14.6-52.5-17.2-46-42.1-82.6-78.8-115.2-32.9-29.2-65.9-48-108-61.3h-.3.3c-3.7-1.2-6.4-1.9-10.2-2.9-2-.6-3.8-1-5.8-1.5-1.1-.3-2.5-1-4.4-1.5C139.7-43.3-68.5,243.2,75.7,475.8c8.2,13.2,14.8,22.9,24.8,35,20.8,25.1,43.5,45.1,70,64.2-33.3,10.4-66.9,20-100.3,30.3-7,2.1-14.2,5.7-20.8,7.7-1.4.4-39.4,11.7-39.4,11.7h52.5c179,.3,358,.2,536.9,0h75.9l-23.3-7.3h.1Z"/>
                            </g>
                            <path id="credipath-chart-path" class="st0" d="M490.9,123.9c-11.8,5.4-23.7,10.9-35.5,16.3l12.6,8-97.1,153.6c-1.5,2.3-3.9,3.7-6.6,3.7s-5.2-1.2-6.8-3.5c-3.3-4.9-8.9-7.8-14.9-7.8s-11.4,2.9-14.7,7.7l-55.1,78.4c-1.4,2-3.6,3.2-6,3.4-2.4.2-4.8-.8-6.4-2.6l-13.4-14.5c-3.4-3.6-7.9-5.7-12.9-5.8-4.9-.1-9.6,1.8-13.1,5.3l-73.7,73.7,7.1,7.1,73.7-73.7c1.5-1.5,3.6-2.3,5.8-2.3s0,0,0,0c2.2,0,4.2,1,5.7,2.6l13.4,14.5c3.7,4,9,6.1,14.4,5.7,5.5-.4,10.4-3.1,13.5-7.6l55.1-78.4c1.5-2.2,3.9-3.4,6.6-3.4s5.1,1.3,6.6,3.5c3.5,5,9.1,7.9,15.2,7.8s11.6-3.3,14.9-8.4l97.1-153.6,12.7,8c.6-12.6,1.2-25.1,1.8-37.7h0Z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="credipath-loading-text">Loading</div>
                <div class="credipath-progress-bar">
                    <div class="credipath-progress-fill"></div>
                </div>
            </div>
        </div>

        @if (!Request::is('client/login'))
        @include('frontend.components.header')
        @endif

        @yield('content')
        @if (!Request::is('client/login'))
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

        <!-- Preloader Hide Script -->
        <script>
            window.addEventListener('load', function() {
                const preloader = document.getElementById('preloader');
                if (preloader) {
                    preloader.classList.add('hide-loader');
                    setTimeout(function() {
                        preloader.style.display = 'none';
                    }, 500);
                }
            });
        </script>

        @stack('scripts')

    </div> <!-- /.main-page-wrapper -->

</body>

</html>