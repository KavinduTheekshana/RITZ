	<div class="blog-section-two position-relative mt-150 lg-mt-80 mb-150 lg-mb-80">
			<div class="container">
				<div class="position-relative">
					<div class="row gx-xxl-5">

						@forelse($blogs as $blog)

						<div class="col-md-6">
							<article class="blog-meta-two mb-80 lg-mb-50 wow fadeInUp">
								<figure class="post-img rounded-5 position-relative d-flex align-items-end m0" style="background-image: url({{ asset('storage/' . $blog->image) }});">
									<a href="{{ route('blog.show', $blog->slug) }}" class="stretched-link rounded-5 date tran3s">{{ $blog->created_at->format('M d, Y') }}</a>
								</figure>
								<div class="post-data">
									<div class="d-flex justify-content-between align-items-center flex-wrap">
										<a href="{{ route('blog.show', $blog->slug) }}" class="blog-title"><h4>{{ $blog->title }}</h4></a>
										<a href="{{ route('blog.show', $blog->slug) }}" class="round-btn rounded-circle d-flex align-items-center justify-content-center tran3s"><i class="bi bi-arrow-up-right"></i></a>
									</div>
									<div class="post-info">{{ $blog->author }} | {{ $blog->category }}</div>
								</div>
							</article>
							<!-- /.blog-meta-two -->
						</div>

						@empty

						<div class="post-info text-center">No blog posts found.</div>
					@endforelse


					</div>


					{{ $blogs->links('vendor.pagination.custom') }}

                    <!-- /.pagination-one -->
				</div>
			</div>
		</div>