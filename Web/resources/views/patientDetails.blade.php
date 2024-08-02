@extends('layouts.master')

@section('title') Patient Details @endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Patient @endslot
        @slot('title') Patient Details @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <!-- Card for Patient Info -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Patient: {{$client->name." ".$client->surname}}</h4>
                        <a href="{{ route('download.pdf', $client->id) }}" class="btn btn-primary">Download PDF</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap">
                            <tbody>
                            <tr><td>Email:</td><td>{{$client->email}}</td></tr>
                            <tr><td>Date of Birth:</td><td>{{$client->birth}}</td></tr>
                            <tr><td>Phone Number:</td><td>{{$client->telephone_number}}</td></tr>
                            <tr><td>Address:</td><td>{{ $client->address ?: 'Not Specified' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <div class="row">
                <div class="col-lg-12">
                        <!-- Analysis Summary-->
                        <div class="container mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title mb-4 text-center" style="font-size: 1.3rem;">Analysis Summary</h3>
                                    <div class="row">

                                        <!-- Totals Card -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">Totals</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="mb-2"><strong>Extremely High:</strong> {{ $data['totals_and_durations']['totals']['extremely_high'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Extremely Low:</strong> {{ $data['totals_and_durations']['totals']['extremely_low'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>High:</strong> {{ $data['totals_and_durations']['totals']['high'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Low:</strong> {{ $data['totals_and_durations']['totals']['low'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Normal:</strong> {{ $data['totals_and_durations']['totals']['normal'] ?? 'N/A' }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Percentage Breakdown Card -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">Percentage Breakdown</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="mb-2"><strong>Extremely High:</strong> {{ number_format((float)($data['totals_and_durations']['percentages']['extremely_high'] ?? 0), 2) }}%</li>
                                                        <li class="mb-2"><strong>Extremely Low:</strong> {{ number_format((float)($data['totals_and_durations']['percentages']['extremely_low'] ?? 0), 2) }}%</li>
                                                        <li class="mb-2"><strong>High:</strong> {{ number_format((float)($data['totals_and_durations']['percentages']['high'] ?? 0), 2) }}%</li>
                                                        <li class="mb-2"><strong>Low:</strong> {{ number_format((float)($data['totals_and_durations']['percentages']['low'] ?? 0), 2) }}%</li>
                                                        <li class="mb-2"><strong>Normal:</strong> {{ number_format((float)($data['totals_and_durations']['percentages']['normal'] ?? 0), 2) }}%</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Max Anomalous Day Card -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">Max Anomalous Day</h5>
                                                    <p><strong>Date:</strong> {{ $data['totals_and_durations']['max_anomalous_day']['date'] ?? 'N/A' }}</p>
                                                    <ul class="list-unstyled">
                                                        <li class="mb-2"><strong>Extremely High Count:</strong> {{ $data['totals_and_durations']['max_anomalous_day']['details']['Extremely High Count'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Extremely Low Count:</strong> {{ $data['totals_and_durations']['max_anomalous_day']['details']['Extremely Low Count'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>High Count:</strong> {{ $data['totals_and_durations']['max_anomalous_day']['details']['High Count'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Low Count:</strong> {{ $data['totals_and_durations']['max_anomalous_day']['details']['Low Count'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Total Count:</strong> {{ $data['totals_and_durations']['max_anomalous_day']['details']['Total Count'] ?? 'N/A' }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Glycemic Swings Statistics Card -->
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">Time Swings Statistics</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="mb-2"><strong>Percentage of Time Swing With Too Long Glucose Anomalies:</strong> {{ number_format((float)($data['totals_and_durations']['time_swings_stats']['percentage_time_swing_too_long'] ?? 0), 2) }}%</li>
                                                        <li class="mb-2"><strong>Percentage of Too frequent Time Swings:</strong> {{ number_format((float)($data['totals_and_durations']['time_swings_stats']['percentage_too_frequent_time_swings'] ?? 0), 2) }}%</li>
                                                        <li class="mb-2"><strong>Total Time Swing:</strong> {{ $data['totals_and_durations']['time_swings_stats']['total_time_swings'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Total Time Swing With Too Long Glucose Anomalies:</strong> {{ $data['totals_and_durations']['time_swings_stats']['total_time_swing_too_long'] ?? 'N/A' }}</li>
                                                        <li class="mb-2"><strong>Total Day of Too Frequent Time Swings:</strong> {{ $data['totals_and_durations']['time_swings_stats']['total_too_frequent_time_swings'] ?? 'N/A' }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h4 class="card-title mb-3 text-center" style="font-size: 1.3rem;">Analysis Results</h4>

                    <!-- Glycemic Swings Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Time Swing</h5>
                            @if(isset($data['time_swing']) && count($data['time_swing']) > 0)
                                <div class="table-responsive">
                                    <table id="datatable-glycemic-swings" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>First Event</th>
                                            <th>Second Event</th>
                                            <th>Duration Time Swing (h)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data['time_swing'] as $swing)
                                            <tr>
                                                <td>{{ $swing['day'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['first_event'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['second_event'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['duration_time_swing'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Nessun dato trovato per time swings.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Swings Followed By Anomalous Duration Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Too Long Glucose Anomalies</h5>
                            @if(isset($data['too_long_glucose_anomalies']) && count($data['too_long_glucose_anomalies']) > 0)
                                <div class="table-responsive">
                                    <table id="datatable-swings-followed-by-duration" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Event</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Durations (h)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data['too_long_glucose_anomalies'] as $swing)
                                            <tr>
                                                <td>{{ $swing['day'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['event'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['start_time'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['end_time'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['duration'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Nessun dato trovato per swings followed by anomalous duration.</p>
                            @endif
                        </div>
                    </div>



                    <!-- Anomalous Frequency Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Too Frequent Glucose Anomalies</h5>
                            @if(isset($data['too_frequent_glucose_anomalies']) && count($data['too_frequent_glucose_anomalies']) > 0)
                                <div class="table-responsive">
                                    <table id="datatable-anomalous-frequency" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>High Count</th>
                                            <th>Low Count</th>
                                            <th>Extremely High Count</th>
                                            <th>Extremely Low Count</th>
                                            <th>Total Count</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data['too_frequent_glucose_anomalies'] as $frequency)
                                            <tr>
                                                <td>{{ $frequency['day'] ?? 'N/A' }}</td>
                                                <td>{{ $frequency['high_count'] ?? 'N/A' }}</td>
                                                <td>{{ $frequency['low_count'] ?? 'N/A' }}</td>
                                                <td>{{ $frequency['extremely_high_count'] ?? 'N/A' }}</td>
                                                <td>{{ $frequency['extremely_low_count'] ?? 'N/A' }}</td>
                                                <td>{{ $frequency['total_count'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Nessun dato trovato per anomalous frequency.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Swings Followed By Anomalous Frequency Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Too Frequent Time Swings</h5>
                            @if(isset($data['too_frequent_time_swings']) && count($data['too_frequent_time_swings']) > 0)
                                <div class="table-responsive">
                                    <table id="datatable-swings-followed-by-frequency" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>First Swing Event</th>
                                            <th>Second Swing Event</th>
                                            <th>Duration Time Swing (h)</th>
                                            <th>Frequency</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data['too_frequent_time_swings'] as $swing)
                                            <tr>
                                                <td>{{ $swing['Events'][0]['Day'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['Events'][0]['First event'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['Events'][0]['Second event'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['Events'][0]['Duration time swing'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['Number of Time Swings'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Nessun dato trovato per time swings too frequent.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Swings Followed By Anomalous Duration Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Time Swing With Too Long Glucose Anomalies</h5>
                            @if(isset($data['time_swing_with_too_long_glucose_anomalies']) && count($data['time_swing_with_too_long_glucose_anomalies']) > 0)
                                <div class="table-responsive">
                                    <table id="datatable-swings-followed-by-duration" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>First Swing Event</th>
                                            <th>Second Swing Event</th>
                                            <th>Duration Time Swing (h)</th>
                                            <th>Duration(h)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data['time_swing_with_too_long_glucose_anomalies'] as $swing)
                                            <tr>
                                                <td>{{ $swing['day'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['first_event'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['second_event'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['duration_time_swing'] ?? 'N/A' }}</td>
                                                <td>{{ $swing['anomalous_durations'] ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Nessun dato trovato per swings followed by anomalous duration.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <!-- apexcharts -->
    <script>
        $(document).ready(function() {
            $('#datatable-anomalous-duration').DataTable();
            $('#datatable-anomalous-frequency').DataTable();
            $('#datatable-glycemic-swings').DataTable();
            $('#datatable-swings-followed-by-duration').DataTable();
            $('#datatable-swings-followed-by-frequency').DataTable();
        });

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

