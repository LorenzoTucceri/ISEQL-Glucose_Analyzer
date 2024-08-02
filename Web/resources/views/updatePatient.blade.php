@extends('layouts.master')

@section('title') Edit Patient @endsection

@section('css')
    <!-- bootstrap datepicker -->
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- dropzone css -->
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Patient @endslot
        @slot('title') Edit Patient @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4 fw-bolder">Update Patient</h4>

                    @error('error')
                    <div class="alert alert-danger" role="alert">
                        {{ $message }}
                    </div>
                    @enderror

                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('updatePatient') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="client_id" value="{{ $client->id }}">

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label">*First Name</label>
                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Enter first name" value="{{ old('name', $client->name) }}" required>
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
                                           placeholder="Enter last name" value="{{ old('surname', $client->surname) }}" required>
                                    @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="telephone_number" class="form-label">*Phone Number</label>
                                    <input id="telephone_number" name="telephone_number" type="text" class="form-control @error('telephone_number') is-invalid @enderror"
                                           placeholder="Enter phone number" value="{{ old('telephone_number', $client->telephone_number) }}" pattern="\+?[0-9]*" required>
                                    @error('telephone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">*Email</label>
                                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Enter email" value="{{ old('email', $client->email) }}" required>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="birth" class="form-label">*Date of Birth</label>
                                    <div class="input-group" id="datepicker">
                                        <input type="text" name="birth" class="form-control @error('birth') is-invalid @enderror"
                                               placeholder="Enter date of birth" data-date-format="yyyy-mm-dd" data-date-container='#datepicker'
                                               data-provide="datepicker" data-date-autoclose="true" value="{{ old('birth', $client->birth) }}" required>
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                    @error('birth')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">*Address</label>
                                    <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                           placeholder="Enter address" value="{{ old('address', $client->address) }}" required>
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                         <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="csv" class="form-label">CSV</label>
                                    <input id="csv" name="csv" type="file" class="form-control @error('csv') is-invalid @enderror">
                                    @error('csv')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div><br><br>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                        </div><br>
                    </form>

                    <p class="text-left">*If you select a new file, it will overwrite the last CSV</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
@endsection
