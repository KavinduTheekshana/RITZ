@extends('layouts.frontend')

@section('title', "$blog->title | Ritz Accounting & Advisory")
@section('meta_description', $blog->meta_description)
@section('meta_keywords', $blog->meta_keywords)
@section('ogimage', asset(url('storage/' . $blog->image)))

@section('content')

@section('single_page_img', asset('storage/' . $blog->image))
@section('single_page_name', 'Blog')
@section('single_page_title', $blog->title)
@include('frontend.components.inner-banner')


<div class="blog-details position-relative mt-150 lg-mt-80 mb-150 lg-mb-80">
    <div class="container">
        <div class="row gx-xl-5">
            <div class="col-lg-8">
                <article class="blog-meta-two style-two">
                    <figure class="post-img position-relative d-flex align-items-end m0"
                        style="background-image: url({{ asset('storage/' . $blog->image) }});">
                        <div class="date">{{ $blog->created_at->format('M d, Y') }}</div>
                    </figure>
                    <div class="post-data">
                        <div class="post-info">{{ $blog->author }} | {{ $blog->category }}</div>
                        <div class="blog-title">
                            <h4>{{ $blog->title }}</h4>
                        </div>
                        <div class="post-details-meta">
                            <p> {!! $blog->content !!}</p>


                        </div>
                        <!-- /.post-details-meta -->
                        <!-- Add this before the blog-navigation div in your show.blade.php -->


                        <div class="bottom-widget d-sm-flex align-items-start justify-content-between flex-wrap">
                            @if ($blog->tags || true)
                            <ul class="d-flex align-items-center tags style-none pt-20 flex-wrap">
                                <li>Tag:</li>
                                @foreach (explode(',', $blog->tags) as $tag)
                                <li><a href="{{ route('blog.tag', trim($tag)) }}">{{ trim($tag) }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                            <ul class="d-flex share-icon align-items-center style-none pt-20">
                                <li>Share:</li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $blog->slug)) }}"
                                        target="_blank">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}"
                                        target="_blank">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $blog->slug)) }}"
                                        target="_blank">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- /.post-data -->
                </article>


            </div>

            <div class="col-lg-4 col-md-8">
                <div class="blog-sidebar md-mt-80 ps-xxl-4">
                    <form action="#" class="sidebar-search">
                        <input type="text" placeholder="Search..">
                        <button class="tran3s"><i class="bi bi-search"></i></button>
                    </form>
                    <!-- resources/views/blogs/partials/sidebar.blade.php -->

                    <div class="blog-category mt-60 lg-mt-40">
                        <h3 class="sidebar-title">Category</h3>
                        <ul class="style-none">


                            @foreach ($categories as $categoryItem)
                                <li>
                                    <a href="{{ route('blog.category', $categoryItem->category) }}">
                                        {{ $categoryItem->category }} <span>({{ $categoryItem->post_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.blog-category -->
                    <div class="blog-recent-news mt-60 lg-mt-40">
                        <h3 class="sidebar-title">Recent News</h3>



                        @foreach ($recentPosts as $post)
                            <article class="recent-news">
                                <figure class="post-img"
                                    style="background-image: url({{ asset('storage/' . $post->image) }});">
                                </figure>
                                <div class="post-data">
                                    <div class="date">{{ $post->created_at->format('d M Y') }}</div>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="blog-title">
                                        <h3>{{ $post->title }}</h3>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <!-- /.blog-recent-news -->

                    <!-- Add this to your sidebar.blade.php or directly in show.blade.php -->

                    <!-- Add this to your sidebar.blade.php or directly in show.blade.php -->

                    <div class="blog-keyword mt-60 lg-mt-40">
                        <h3 class="sidebar-title">Keywords</h3>
                        <ul class="style-none d-flex flex-wrap">


                            @foreach ($uniqueKeywords as $keyword)
                                <li><a href="{{ route('blog.tag', $keyword) }}">{{ $keyword }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.blog-keyword -->
                    <div class="contact-banner text-center mt-50 lg-mt-30">
                        <h3 class="mb-20">Any Questions? <br>Let’s talk</h3>
                        <a href="{{ route('contact') }}" class="tran3s fw-500">Let’s Talk</a>
                    </div>
                    <!-- /.contact-banner -->
                </div>
                <!-- /.blog-sidebar -->
            </div>
        </div>
    </div>
</div>

@endsection
