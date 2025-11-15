<div class="row mt-80 lg-mt-40">
    <div class="col-xl-5 col-md-6">
        <div class="title-one mb-40">
            <h2 class="color-deep">Get Your Free Consultation Now!</h2>
        </div>
        <!-- /.title-one -->
        <div class="form-style-two">
            <!-- Updated contact form for Laravel -->
            <form method="POST" action="{{ route('contact.store') }}" id="contact-form" data-toggle="validator">
                @csrf
                <div class="messages"></div>
                <div class="row controls">
                    <div class="col-12">
                        <div class="input-group-meta form-group mb-40">
                            <input type="text" placeholder="Full Name*" name="name" required="required"
                                data-error="Name is required.">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group-meta form-group mb-40">
                            <input type="email" placeholder="Your Email*" name="email" required="required"
                                data-error="Valid email is required.">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group-meta form-group mb-35">
                            <textarea placeholder="We're here to help you" name="message" required="required"
                                data-error="Please, leave us a message."></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn-twentyOne rounded-2 tran3s w-100 d-block">Send
                            Message</button>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-35">
                            <x-turnstile::turnstile-widget data-theme="light" />
                        </div>
                    </div>
                </div>
            </form>
            <br>

            <div id="form-message-success" style="display:none;" class="alert alert-success">Message sent successfully!
            </div>
            <div id="form-message-error" style="display:none;" class="alert alert-danger">There was an error sending the
                message.</div>

        </div> <!-- /.form-style-two -->
    </div>

    <div class="col-md-6 ms-auto me-auto me-lg-0">
        <img src="{{ asset('frontend/images/assets/contact-inner.webp') }}" alt=""
            class="media-img ms-auto sm-mt-50">
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#contact-form').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    beforeSend: function() {
                        // Optionally show loading spinner or disable button
                    },
                    success: function(response) {
                        $('#form-message-success').show().text(response.message);
                        $('#form-message-error').hide();
                        form[0].reset();

                        // Reset Turnstile widget
                        if (typeof turnstile !== 'undefined') {
                            turnstile.reset();
                        }

                        // Hide success message after 10 seconds
                        setTimeout(function() {
                            $('#form-message-success').fadeOut();
                        }, 10000);
                    },
                    error: function(xhr) {
                        $('#form-message-success').hide();
                        let errorMsg = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        $('#form-message-error').show().text(errorMsg);

                        // Reset Turnstile widget on error
                        if (typeof turnstile !== 'undefined') {
                            turnstile.reset();
                        }

                        // Hide error message after 10 seconds
                        setTimeout(function() {
                            $('#form-message-error').fadeOut();
                        }, 10000);
                    }
                });
            });
        });
    </script>
@endpush
