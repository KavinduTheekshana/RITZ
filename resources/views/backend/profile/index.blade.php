@extends('layouts.backend')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            @section('page_name', 'My Profile')
            @include('backend.components.breadcrumb')
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-lg-5 col-xxl-3">
                            <div class="card overflow-hidden">
                                <div class="card-body position-relative">
                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-90 img-thumbnail"
                                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('client')->user()->first_name . ' ' . Auth::guard('client')->user()->last_name) }}&background=random&color=fff&size=100"
                                                alt="User image">
                                            <i class="chat-badge bg-success me-2 mb-2"></i>
                                        </div>
                                        <h5 class="mb-0">
                                            {{ Auth::guard('client')->user()->title . ' ' . Auth::guard('client')->user()->first_name . ' ' . Auth::guard('client')->user()->middle_name . ' ' . Auth::guard('client')->user()->last_name }}
                                        </h5>
                                        <p class="text-muted text-sm">{{ Auth::guard('client')->user()->email }}</p>
                                    </div>
                                </div>
                                <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0"
                                    id="user-set-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link list-group-item list-group-item-action active"
                                        id="user-set-profile-tab" data-bs-toggle="pill" href="#user-set-profile"
                                        role="tab" aria-controls="user-set-profile" aria-selected="true">
                                        <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Profile
                                            Overview</span>
                                    </a>
                                    <a class="nav-link list-group-item list-group-item-action" id="user-set-passwort-tab"
                                        data-bs-toggle="pill" href="#user-set-passwort" role="tab"
                                        aria-controls="user-set-passwort" aria-selected="false">
                                        <span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Change
                                            Password</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5>Personal Information</h5>
                                </div>
                                <div class="card-body position-relative">
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                        <p class="mb-0 text-muted me-1">Email</p>
                                        <p class="mb-0">{{ Auth::guard('client')->user()->email }}</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                        <p class="mb-0 text-muted me-1">Phone</p>
                                        <p class="mb-0">{{ Auth::guard('client')->user()->mobile_number ?? Auth::guard('client')->user()->telephone_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                        <p class="mb-0 text-muted me-1">Address</p>
                                        <p class="mb-0">{{ Auth::guard('client')->user()->postal_address ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                        <p class="mb-0 text-muted me-1">Date of Birth</p>
                                        <p class="mb-0">{{ Auth::guard('client')->user()->date_of_birth ? Auth::guard('client')->user()->date_of_birth->format('d/m/Y') : 'Not provided' }}</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100">
                                        <p class="mb-0 text-muted me-1">Nationality</p>
                                        <p class="mb-0">{{ Auth::guard('client')->user()->nationality ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-xxl-9">
                            <div class="tab-content" id="user-set-tabContent">
                                <div class="tab-pane fade show active" id="user-set-profile" role="tabpanel"
                                    aria-labelledby="user-set-profile-tab">

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Personal Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Full Name</p>
                                                            <p class="mb-0">{{ Auth::guard('client')->user()->title }}
                                                                {{ Auth::guard('client')->user()->first_name }}
                                                                {{ Auth::guard('client')->user()->middle_name }}
                                                                {{ Auth::guard('client')->user()->last_name }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Preferred Name</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->preferred_name ?? 'Not set' }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Date of Birth</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->date_of_birth ? Auth::guard('client')->user()->date_of_birth->format('d/m/Y') : 'Not provided' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Marital Status</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->marital_status ?? 'Not provided' }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Email</p>
                                                            <p class="mb-0">{{ Auth::guard('client')->user()->email }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Email Verified</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->email_verified_at ? 'Yes' : 'No' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Telephone</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->telephone_number ?? 'Not provided' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Mobile</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->mobile_number ?? 'Not provided' }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Nationality</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->nationality ?? 'Not provided' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Preferred Language</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->preferred_language ?? 'Not provided' }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0 pb-0">
                                                    <p class="mb-1 text-muted">Postal Address</p>
                                                    <p class="mb-0">{{ Auth::guard('client')->user()->postal_address ?? 'Not provided' }}
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Identity & Verification</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">National Insurance Number</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->ni_number ?? 'Not provided' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Personal UTR Number</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->personal_utr_number ?? 'Not provided' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Photo ID Verified</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->photo_id_verified ? 'Yes' : 'No' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Address Verified</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->address_verified ? 'Yes' : 'No' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0 pb-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Terms Signed</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->terms_signed ? Auth::guard('client')->user()->terms_signed->format('d/m/Y') : 'Not signed' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Deceased</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->deceased ? 'Yes' : 'No' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Self Assessment</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item px-0 pt-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Self Assessment Client</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->create_self_assessment_client ? 'Yes' : 'No' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Client Does Their Own SA</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->client_does_their_own_sa ? 'Yes' : 'No' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item px-0 pb-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1 text-muted">Previous Address</p>
                                                            <p class="mb-0">
                                                                {{ Auth::guard('client')->user()->previous_address ?? 'Not provided' }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="user-set-passwort" role="tabpanel"
                                    aria-labelledby="user-set-passwort-tab">
                                    <div class="card alert alert-warning p-0">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 me-3">
                                                    <h4 class="alert-heading">Alert!</h4>
                                                    <p class="mb-2">Your Password will expire in every 3 months. So
                                                        change it periodically.</p>
                                                    <a href="#" class="alert-link"><u>Do not share your
                                                            password</u></a>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <img src="../assets/images/application/img-accout-password-alert.png"
                                                        alt="img" class="img-fluid wid-80">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Change Password</h5>
                                        </div>
                                        <div class="card-body">
                                            <form method="POST" action="{{ route('client.password.update') }}" id="passwordForm">
                                                @csrf
                                                @method('PUT')
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item pt-0 px-0">
                                                        <div class="row mb-0">
                                                            <label
                                                                class="col-form-label col-md-4 col-sm-12 text-md-end">Current
                                                                Password <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-md-8 col-sm-12">
                                                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                                    name="current_password" required>
                                                                @error('current_password')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                                <div class="form-text"> Forgot password? <a href="#"
                                                                        class="link-primary">Click here</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item px-0">
                                                        <div class="row mb-0">
                                                            <label class="col-form-label col-md-4 col-sm-12 text-md-end">New
                                                                Password <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 col-sm-12">
                                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                                    name="password" required>
                                                                @error('password')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item pb-0 px-0">
                                                        <div class="row mb-0">
                                                            <label
                                                                class="col-form-label col-md-4 col-sm-12 text-md-end">Confirm
                                                                Password <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 col-sm-12">
                                                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                                    name="password_confirmation" required>
                                                                @error('password_confirmation')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body text-end">
                                            <button type="button" class="btn btn-outline-secondary me-2" onclick="document.getElementById('passwordForm').reset();">Cancel</button>
                                            <button type="submit" form="passwordForm" class="btn btn-primary">Change Password</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    @if(session('success'))
        <script>
            // You can add a toast notification here
            alert('{{ session('success') }}');
        </script>
    @endif

    @if($errors->any())
        <script>
            // Auto-switch to password tab if there are password-related errors
            var passwordTab = new bootstrap.Tab(document.getElementById('user-set-passwort-tab'));
            passwordTab.show();
        </script>
    @endif
@endsection