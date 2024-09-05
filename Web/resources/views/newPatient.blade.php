@extends('layouts.master')

@section('title') New Patient @endsection

@section('css')
    <!-- bootstrap datepicker -->
    <link href="{{URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">

    <!-- dropzone css -->
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Patient @endslot
        @slot('title') New Patient @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4 fw-bolder">Add Patient</h4>
                    @error("error")
                    <div class="alert alert-danger" role="alert">
                        {{$message}}
                    </div>
                    @enderror
                    @if (\Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{Session::get('success')}}
                        </div>
                    @endif
                    <!-- Nav tabs -->

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <form action="{{ route('addPatient') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-pane active" id="home-1" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">*First Name</label>
                                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                                   placeholder="Enter first name" required>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="surname" class="form-label">*Last Name</label>
                                            <input id="surname" name="surname" type="text" class="form-control @error('surname') is-invalid @enderror"
                                                   placeholder="Enter last name" required>
                                            @error('surname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">*Email</label>
                                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                   placeholder="Enter email" required>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="telephone_number" class="form-label">*Phone Number</label>
                                            <input id="telephone_number" name="telephone_number" type="text" class="form-control @error('telephone_number') is-invalid @enderror"
                                                   placeholder="Enter phone number" pattern="\+?[0-9]*" required>
                                            @error('telephone_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="birth" class="form-label">*Date of Birth</label>
                                            <div class="input-group" id="datepicker">
                                                <input type="text" name="birth" class="form-control @error('birth') is-invalid @enderror"
                                                       placeholder="Enter date of birth" data-date-format="yyyy-mm-dd" required
                                                       data-date-container='#datepicker' data-provide="datepicker" data-date-autoclose="true">
                                                @error('birth')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">*Address</label>
                                            <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                                   placeholder="Enter address" required>
                                            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div><br>

                                <div style="text-align:center;">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light w-lg">
                                        Save
                                    </button>
                                </div><br>
                                <p>*Sarà possibile inserire i relativi csv dopo l'immissione del paziente</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end row -->
@endsection

@section('script')

    <!-- bootstrap datepicker -->
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- dropzone plugin -->
    <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{URL::asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/pages/job-list.init.js')}}"></script>
    <script src="{{URL::asset('assets/js/app.min.js')}}"></script>

@endsection
