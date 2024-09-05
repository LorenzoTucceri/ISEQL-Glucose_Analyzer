@extends('layouts.master')

@section('title') Csv Patient @endsection

@section('css')
    <!-- bootstrap datepicker -->
    <link href="{{URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">

    <!-- dropzone css -->
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Csv files @endslot
        @slot('title') Csv Patient @endslot
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
                    <h3 class="card-title mb-3 text-xl-center">Patient {{$patient->name.' '.$patient->surname}}</h3>
                    <h4 class="card-title mb-3">Add csv file</h4>
                    <form action="{{ route('uploadCsv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{$patient->id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input id="csv" name="csv[]" type="file" class="form-control @error('csv') is-invalid @enderror" multiple required>
                                    @error('csv')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light w-lg">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form><br>
                    <h4 class="card-title mb-3">Csv files List</h4>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th>File</th>
                            <th>Start time</th>
                            <th>End time</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file) <!-- $files dovrebbe essere passato dal controller -->
                        <tr>
                            <td>{{ $file->csv_file_path }}</td>
                            <td>{{ $file->start_time }}</td>
                            <td>{{ $file->end_time }}</td>
                            <td>
                                <ul class="list-unstyled hstack gap-1 mb-0">
                                    <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        <a href="{{ route('viewCsv', ['fileId' => $file->id, 'patient' => $patient->id]) }}" target="_blank" class="btn btn-sm btn-soft-primary">
                                            <i class="mdi mdi-eye-outline font-size-15"></i>
                                        </a>
                                    </li>
                                    <li data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                        <a href="#csvDelete" id="{{ $file->id }}" onclick="deleteCsv(this.id)" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger">
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

    <div class="modal fade" id="csvDelete" tabindex="-1" aria-labelledby="jobDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body px-4 py-5 text-center">
                    <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="avatar-sm mb-4 mx-auto">
                        <div class="avatar-title bg-warning text-warning bg-opacity-10 font-size-20 rounded-3">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </div>
                    </div>
                    <p class="text-muted font-size-16 mb-4">Are you sure you want to delete the csv file?</p>
                    <form action="{{ route('deleteCsv') }}" method="POST">
                        @csrf
                        <input type="hidden" name="csv_id" id="boxDelete">
                        <div class="hstack gap-2 justify-content-center mb-0">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- end row -->
@endsection
@section('script')

    <script>
        function deleteCsv(id){
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
