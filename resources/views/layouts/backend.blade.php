<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>@yield('meta_title', 'Client Dashboard | RITZ Accounting')</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="@yield('meta_description', 'Manage your accounting, taxes, and financial services with RITZ. Access your companies, self-assessment, engagement letters, and secure messaging with your accountant.')" />
    <meta name="keywords" content="@yield('meta_keywords', 'RITZ accounting, client dashboard, tax services, self-assessment, company accounts, engagement letters, accountant messaging, financial services UK')" />
    <meta name="author" content="RITZ Accounting" />
    <meta name="robots" content="noindex, nofollow" />

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'Client Dashboard | RITZ Accounting')" />
    <meta property="og:description" content="@yield('og_description', 'Manage your accounting and financial services with RITZ')" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="RITZ Accounting" />

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="@yield('twitter_title', 'Client Dashboard | RITZ Accounting')" />
    <meta name="twitter:description" content="@yield('twitter_description', 'Manage your accounting and financial services with RITZ')" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- [Favicon] icon -->
    {{-- <link rel="icon" href="{{ asset('backend/images/favicon.svg') }}" type="image/x-icon" /> --}}
        <link rel="icon" type="image/png" sizes="56x56" href="{{ asset('frontend/images/logo/Credipath_icon.svg') }}">
    <!-- [Google Font :asda Public Sans] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('backend/fonts/tabler-icons.min.css') }}">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('backend/fonts/feather.css') }}">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('backend/fonts/fontawesome.css') }}">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('backend/fonts/material.css') }}">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('backend/css/style-preset.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/custom.css') }}" media="all">
      @stack('styles')
    {{-- @vite(['', 'resources/js/app.js']) --}}

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr"
    data-pc-theme="light" data-pc-direction="ltr">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    @include('backend.components.header')
    @include('backend.components.slider')
    @yield('content')
    @include('backend.components.footer')
    <script src="{{ asset('backend/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('backend/js/pcoded.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('backend/js/layout-horizontal.js') }}"></script>

    @stack('scripts')

</body>

</html>
