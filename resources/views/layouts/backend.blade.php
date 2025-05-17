<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Layout Horizontal | Light Able Admin & Dashboard Template</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Light Able admin and dashboard template offer a variety of UI elements and pages, ensuring your admin panel is both fast and effective." />
    <meta name="author" content="phoenixcoded" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('backend/images/favicon.svg') }}" type="image/x-icon" />
    <!-- [Google Font : Public Sans] icon -->
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
        @vite(['', 'resources/js/app.js'])

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
</body>

</html>
