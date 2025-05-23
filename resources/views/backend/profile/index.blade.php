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
                                <a class="nav-link list-group-item list-group-item-action" id="user-set-information-tab"
                                    data-bs-toggle="pill" href="#user-set-information" role="tab"
                                    aria-controls="user-set-information" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-clipboard-text m-r-10"></i>Personal
                                        Information</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="user-set-account-tab"
                                    data-bs-toggle="pill" href="#user-set-account" role="tab"
                                    aria-controls="user-set-account" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-notebook m-r-10"></i>Account
                                        Information</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="user-set-passwort-tab"
                                    data-bs-toggle="pill" href="#user-set-passwort" role="tab"
                                    aria-controls="user-set-passwort" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Change
                                        Password</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="user-set-email-tab"
                                    data-bs-toggle="pill" href="#user-set-email" role="tab"
                                    aria-controls="user-set-email" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-envelope-open m-r-10"></i>Email
                                        settings</span>
                                </a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5>Personal information</h5>
                            </div>
                            <div class="card-body position-relative">
                                <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                    <p class="mb-0 text-muted me-1">Email</p>
                                    <p class="mb-0">anshan@gmail.com</p>
                                </div>
                                <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                    <p class="mb-0 text-muted me-1">Phone</p>
                                    <p class="mb-0">(+1-876) 8654 239 581</p>
                                </div>
                                <div class="d-inline-flex align-items-center justify-content-between w-100">
                                    <p class="mb-0 text-muted me-1">Location</p>
                                    <p class="mb-0">New York</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5>Skills</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0">Junior</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <div class="progress progress-primary" style="height: 6px">
                                                    <div class="progress-bar" style="width: 30%"></div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="mb-0 text-muted">30%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0">UX Researcher</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <div class="progress progress-primary" style="height: 6px">
                                                    <div class="progress-bar" style="width: 80%"></div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="mb-0 text-muted">80%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0">Wordpress</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <div class="progress progress-primary" style="height: 6px">
                                                    <div class="progress-bar" style="width: 90%"></div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="mb-0 text-muted">90%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0">HTML</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <div class="progress progress-primary" style="height: 6px">
                                                    <div class="progress-bar" style="width: 30%"></div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="mb-0 text-muted">30%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0">Graphic Design</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <div class="progress progress-primary" style="height: 6px">
                                                    <div class="progress-bar" style="width: 95%"></div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="mb-0 text-muted">95%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0">Code Style</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <div class="progress progress-primary" style="height: 6px">
                                                    <div class="progress-bar" style="width: 75%"></div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="mb-0 text-muted">75%</p>
                                            </div>
                                        </div>
                                    </div>
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
                                                            {{ Auth::guard('client')->user()->preferred_name }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1 text-muted">Date of Birth</p>
                                                        <p class="mb-0">
                                                            {{ Auth::guard('client')->user()->date_of_birth }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1 text-muted">Marital Status</p>
                                                        <p class="mb-0">
                                                            {{ Auth::guard('client')->user()->marital_status }}</p>
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
                                                            {{ Auth::guard('client')->user()->telephone_number }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1 text-muted">Mobile</p>
                                                        <p class="mb-0">
                                                            {{ Auth::guard('client')->user()->mobile_number }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1 text-muted">Nationality</p>
                                                        <p class="mb-0">
                                                            {{ Auth::guard('client')->user()->nationality }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1 text-muted">Preferred Language</p>
                                                        <p class="mb-0">
                                                            {{ Auth::guard('client')->user()->preferred_language }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0 pb-0">
                                                <p class="mb-1 text-muted">Postal Address</p>
                                                <p class="mb-0">{{ Auth::guard('client')->user()->postal_address }}
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
                                                            {{ Auth::guard('client')->user()->ni_number }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1 text-muted">Personal UTR Number</p>
                                                        <p class="mb-0">
                                                            {{ Auth::guard('client')->user()->personal_utr_number }}
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
                                                            {{ Auth::guard('client')->user()->terms_signed }}</p>
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
                                                            {{ Auth::guard('client')->user()->previous_address }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="user-set-information" role="tabpanel"
                                aria-labelledby="user-set-information-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Personal Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" class="form-control" value="Anshan">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" value="Handgun">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" class="form-control" value="New York">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Zip code</label>
                                                    <input type="text" class="form-control" value="956754">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Bio</label>
                                                    <textarea class="form-control">
Hello, I’m Anshan Handgun Creative Graphic Designer & User Experience Designer based in Website, I create digital Products a more Beautiful and usable place. Morbid accusant ipsum. Nam nec tellus at.</textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-0">
                                                    <label class="form-label">Experience</label>
                                                    <select class="form-control">
                                                        <option>Startup</option>
                                                        <option>2 year</option>
                                                        <option>3 year</option>
                                                        <option selected>4 year</option>
                                                        <option>5 year</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Social Network</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="flex-grow-1 me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avtar avtar-xs btn-light-twitter">
                                                            <i class="fab fa-twitter f-16"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Twitter</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button class="btn btn-link-primary">Connect</button>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="flex-grow-1 me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avtar avtar-xs btn-light-facebook">
                                                            <i class="fab fa-facebook-f f-16"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Facebook <small
                                                                class="text-muted f-w-400">/Anshan Handgun</small></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button class="btn btn-link-danger">Remove</button>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avtar avtar-xs btn-light-linkedin">
                                                            <i class="fab fa-linkedin-in f-16"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">Linkedin</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button class="btn btn-link-primary">Connect</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Contact Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Phone</label>
                                                    <input type="text" class="form-control"
                                                        value="(+99) 9999 999 999">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control"
                                                        value="demo@sample.com">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Portfolio Url</label>
                                                    <input type="text" class="form-control"
                                                        value="https://demo.com">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-0">
                                                    <label class="form-label">Address</label>
                                                    <textarea class="form-control">3379  Monroe Avenue, Fort Myers, Florida(33912)</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end btn-page">
                                    <div class="btn btn-outline-secondary">Cancel</div>
                                    <div class="btn btn-primary">Update Profile</div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="user-set-account" role="tabpanel"
                                aria-labelledby="user-set-account-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>General Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 pt-0">
                                                <div class="row mb-0">
                                                    <label
                                                        class="col-form-label col-md-4 col-sm-12 text-md-end">Username
                                                        <span class="text-danger">*</span></label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input type="text" class="form-control"
                                                            value="Ashoka_Tano_16">
                                                        <div class="form-text">
                                                            Your Profile URL: <a href="#"
                                                                class="link-primary">https://pc.com/Ashoka_Tano_16</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row mb-0">
                                                    <label
                                                        class="col-form-label col-md-4 col-sm-12 text-md-end">Account
                                                        Email <span class="text-danger">*</span></label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input type="text" class="form-control"
                                                            value="demo@sample.com">
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row mb-0">
                                                    <label
                                                        class="col-form-label col-md-4 col-sm-12 text-md-end">Language</label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <select class="form-control">
                                                            <option>Washington</option>
                                                            <option>India</option>
                                                            <option>Africa</option>
                                                            <option>New York</option>
                                                            <option>Malaysia</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0 pb-0">
                                                <div class="row mb-0">
                                                    <label class="col-form-label col-md-4 col-sm-12 text-md-end">Sign
                                                        in Using <span class="text-danger">*</span></label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <select class="form-control">
                                                            <option>Password</option>
                                                            <option>Face Recognition</option>
                                                            <option>Thumb Impression</option>
                                                            <option>Key</option>
                                                            <option>Pin</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Advance Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 pt-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <p class="mb-1">Secure Browsing</p>
                                                        <p class="text-muted text-sm mb-0">Browsing Securely ( https )
                                                            when it's necessary</p>
                                                    </div>
                                                    <div class="form-check form-switch p-0">
                                                        <input class="form-check-input h4 position-relative m-0"
                                                            type="checkbox" role="switch" checked="">
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <p class="mb-1">Login Notifications</p>
                                                        <p class="text-muted text-sm mb-0">Notify when login attempted
                                                            from other place</p>
                                                    </div>
                                                    <div class="form-check form-switch p-0">
                                                        <input class="form-check-input h4 position-relative m-0"
                                                            type="checkbox" role="switch" checked="">
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0 pb-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <p class="mb-1">Login Approvals</p>
                                                        <p class="text-muted text-sm mb-0">Approvals is not required
                                                            when login from unrecognized
                                                            devices.</p>
                                                    </div>
                                                    <div class="form-check form-switch p-0">
                                                        <input class="form-check-input h4 position-relative m-0"
                                                            type="checkbox" role="switch" checked="">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Recognized Devices</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 pt-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="me-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avtar bg-light-primary">
                                                                <i class="ph-duotone ph-desktop f-24"></i>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="mb-1">Celt Desktop</p>
                                                                <p class="mb-0 text-muted">4351 Deans Lane</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <div class="text-success d-inline-block me-2">
                                                            <i class="fas fa-circle f-10 me-2"></i>
                                                            Current Active
                                                        </div>
                                                        <a href="#!" class="text-danger"><i
                                                                class="feather icon-x-circle"></i></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="me-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avtar bg-light-primary">
                                                                <i class="ph-duotone ph-device-tablet-camera f-24"></i>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="mb-1">Imco Tablet</p>
                                                                <p class="mb-0 text-muted">4185 Michigan Avenue</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <div class="text-muted d-inline-block me-2">
                                                            <i class="fas fa-circle f-10 me-2"></i>
                                                            Active 5 days ago
                                                        </div>
                                                        <a href="#!" class="text-danger"><i
                                                                class="feather icon-x-circle"></i></a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0 pb-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="me-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avtar bg-light-primary">
                                                                <i class="ph-duotone ph-device-mobile-camera f-24"></i>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="mb-1">Albs Mobile</p>
                                                                <p class="mb-0 text-muted">3462 Fairfax Drive</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <div class="text-muted d-inline-block me-2">
                                                            <i class="fas fa-circle f-10 me-2"></i>
                                                            Active 1 month ago
                                                        </div>
                                                        <a href="#!" class="text-danger"><i
                                                                class="feather icon-x-circle"></i></a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Active Sessions</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 pt-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="me-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avtar bg-light-primary">
                                                                <i class="ph-duotone ph-desktop f-24"></i>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="mb-1">Celt Desktop</p>
                                                                <p class="mb-0 text-muted">4351 Deans Lane</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-link-danger">Logout</button>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0 pb-0">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="me-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avtar bg-light-primary">
                                                                <i class="ph-duotone ph-device-tablet-camera f-24"></i>
                                                            </div>
                                                            <div class="ms-2">
                                                                <p class="mb-1">Moon Tablet</p>
                                                                <p class="mb-0 text-muted">4185 Michigan Avenue</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-link-danger">Logout</button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body text-end">
                                        <button class="btn btn-outline-dark me-2">Clear</button>
                                        <button class="btn btn-primary">Update Profile</button>
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
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item pt-0 px-0">
                                                <div class="row mb-0">
                                                    <label
                                                        class="col-form-label col-md-4 col-sm-12 text-md-end">Current
                                                        Password <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input type="password" class="form-control">
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
                                                        <input type="password" class="form-control">
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item pb-0 px-0">
                                                <div class="row mb-0">
                                                    <label
                                                        class="col-form-label col-md-4 col-sm-12 text-md-end">Confirm
                                                        Password <span class="text-danger">*</span></label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input type="password" class="form-control">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body text-end">
                                        <div class="btn btn-outline-secondary me-2">Cancel</div>
                                        <div class="btn btn-primary">Change Password</div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="user-set-email" role="tabpanel"
                                aria-labelledby="user-set-email-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Email Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mb-3">Setup Email Notification</h6>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">Email Notification</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch" checked="">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-0">
                                            <div>
                                                <p class="text-muted mb-0">Send Copy To Personal Email</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Activity Related Emails</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mb-3">When to email?</h6>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">Have new notifications</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch" checked="">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">You're sent a direct message</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">Someone adds you as a connection</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch" checked="">
                                            </div>
                                        </div>
                                        <hr class="my-2 border border-secondary-subtle">
                                        <h6 class="mb-3">When to escalate emails?</h6>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">Upon new order</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch" checked="">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">New membership approval</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-0">
                                            <div>
                                                <p class="text-muted mb-0">Member registration</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch" checked="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Updates from System Notification</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mb-3">Email you with?</h6>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">News about PCT-themes products and feature
                                                    updates</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch" checked="">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">Tips on getting more out of PCT-themes</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch" checked="">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">Things you missed since you last logged into
                                                    PCT-themes</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <p class="text-muted mb-0">News about products and other services</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-0">
                                            <div>
                                                <p class="text-muted mb-0">Tips and Document business products</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="m-0 form-check-input h5 position-relative"
                                                    type="checkbox" role="switch">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body text-end btn-page">
                                        <div class="btn btn-outline-secondary">Cancel</div>
                                        <div class="btn btn-primary">Update Profile</div>
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
@endsection
