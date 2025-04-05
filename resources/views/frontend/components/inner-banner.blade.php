<div class="inner-banner-one pt-225 lg-pt-200 md-pt-150 pb-100 md-pb-70 position-relative" style="background-image: url(@yield('single_page_img'));">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <ul class="style-none d-inline-flex pager ml-25 text-white">
                    <li><a href="{{route('/')}}">Home</a></li>
                    <li>/</li>
                    <li>@yield('single_page_name')</li>
                </ul>
                <h1 class="hero-heading position-relative pt-40 text-white ">@yield('single_page_title')</h1>
            </div>
        </div>
    </div>
</div>

{{-- <div class="inner-banner-one pt-225 lg-pt-200 md-pt-150 pb-100 md-pb-70 position-relative" style="background-image: url(../frontend/images/assets/contact.webp);">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <ul class="style-none d-inline-flex pager ml-25 text-white">
                    <li><a href="{{route('/')}}">Home</a></li>
                    <li>/</li>
                    <li>Contact</li>
                </ul>
                <h1 class="hero-heading d-inline-block position-relative pt-40 text-white ">Contact us for inquiries</h1>
            </div>
        </div>
    </div>
</div> --}}