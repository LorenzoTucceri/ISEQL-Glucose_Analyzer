@extends('layouts.master')

@section('title') Patient Management @endsection

@section('css')
    <!-- bootstrap datepicker -->
    <link href="{{URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">

    <!-- dropzone css -->
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Patients @endslot
        @slot('title') Patient Management @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
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

                    <h4 class="card-title mb-4">Patient List</h4>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th>Full name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\Patient::all() as $patient)
                            <tr>
                                <td>{{$patient->name}} {{$patient->surname}}</td>
                                <td>{{$patient->email}}</td>
                                <td>{{$patient->address}}</td>
                                <td>
                                    <ul class="list-unstyled hstack gap-1 mb-0">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                            <a href="{{ route('showCsvPatient', $patient->id) }}" target="_blank" class="btn btn-sm btn-soft-primary">
                                                <i class="mdi mdi-eye-outline font-size-15"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            <a href="{{route('searchPatient', $patient->id)}}" class="btn btn-sm btn-soft-danger">
                                                <i class="mdi mdi-pencil-outline font-size-15"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <a href="#patientDelete" id="{{$patient->id}}" onclick="deletePatient(this.id)" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger">
                                                <i class="mdi mdi-delete-outline font-size-15"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="patientDelete" tabindex="-1" aria-labelledby="jobDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body px-4 py-5 text-center">
                    <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="avatar-sm mb-4 mx-auto">
                        <div class="avatar-title bg-warning text-warning bg-opacity-10 font-size-20 rounded-3">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </div>
                    </div>
                    <p class="text-muted font-size-16 mb-4">Are you sure you want to delete the patient?</p>
                    <form action="{{route('deletePatient')}}" method="post"> @csrf
                        <input type="hidden" name="patient" id="boxDelete">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end row -->
@endsection
@section('script')

    <script>
        function deletePatient(id){
            document.getElementById('boxDelete').value = id;
        }
    </script>

    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- project-overview init -->
    <script src="{{ URL::asset('/assets/js/pages/project-overview.init.js') }}"></script>

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

@endsection
