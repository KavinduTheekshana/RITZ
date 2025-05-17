@extends('layouts.backend')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
        @section('page_name', 'Dashboard')
        @include('backend.components.breadcrumb')


        <div class="row">

            <div class="col-md-4 col-sm-12">
                <div class="card statistics-card-1 overflow-hidden  bg-brand-color-3">
                    <div class="card-body">
                        <img src="{{ asset('backend/images/widget/img-status-6.svg') }}" alt="img"
                            class="img-fluid img-bg">
                        <h5 class="mb-4 text-white">Your Companies</h5>
                        <div class="d-flex align-items-center mt-3">

                            <h3 class="text-white f-w-300 d-flex align-items-center m-b-0">
                                @if ($companies->count())
                                    <ul>
                                        @foreach ($companies as $company)
                                            <li>{{ $company->company_name }} ({{ $company->company_type }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No companies linked.</p>
                                @endif
                            </h3>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-4 col-sm-6">
                <div class="card statistics-card-1 overflow-hidden ">
                    <div class="card-body">
                        <img src="{{ asset('backend/images/widget/img-status-4.svg') }}" alt="img"
                            class="img-fluid img-bg">
                        <h5 class="mb-4">Your Self Assessment</h5>
                        <div class="d-flex align-items-center mt-3">
                            @if ($selfAssessment)
                                <h3 class="f-w-300 d-flex align-items-center m-b-0">
                                    <ul>
                                        <li>{{ $selfAssessment->assessment_name }} <span class="badge bg-light-success ms-2 text-sm">Personal</span></li>
                                    </ul>
                                    </h3>
                               
                            @else
                                <p>No self assessment data found.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>



        </div>

    </div>
</div>
@endsection
