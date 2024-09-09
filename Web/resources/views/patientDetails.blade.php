@extends('layouts.master')

@section('title') Patient Details @endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Patient @endslot
        @slot('title') Patient Details @endslot
    @endcomponent

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        /* Stile per il contenitore dell'input per centrarlo */
        .daterange-container {
            display: flex;
            justify-content: center; /* Allinea orizzontalmente al centro */
            align-items: center;     /* Allinea verticalmente al centro */
            margin: 0 auto;          /* Centra il contenitore all'interno del suo genitore */
            max-width: 400px;        /* Imposta una larghezza massima per l'input */
        }

        /* Stile per l'input del daterangepicker */
        input[name="daterange"] {
            width: 100%;            /* Occupa tutta la larghezza del contenitore */
            max-width: 300px;       /* Imposta una larghezza massima per l'input */
            padding: 0.5rem;        /* Padding interno ridotto per ridurre le dimensioni */
            border: 1px solid #ced4da; /* Colore del bordo grigio chiaro */
            border-radius: 5px;    /* Arrotonda gli angoli del box */
            font-size: 0.875rem;    /* Dimensione del testo più piccola */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombra leggera per effetto sollevato */
            transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Transizione per colore del bordo e ombra */
            background-color: #ffffff; /* Colore di sfondo bianco */
        }

        /* Stile per l'input del daterangepicker quando è in focus */
        input[name="daterange"]:focus {
            border-color: #007bff; /* Colore del bordo blu quando è attivo */
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.5); /* Ombra blu attorno all'input quando è attivo */
            outline: none; /* Rimuove l'outline predefinito */
        }

        /* Contenitore del bottone per centrarlo */
        .form-container {
            display: flex;
            justify-content: center; /* Allinea orizzontalmente al centro */
            align-items: center;     /* Allinea verticalmente al centro se necessario */
        }

        /* Stile per il bottone */
        .btn-center {
            display: inline-block; /* Mantiene il bottone in linea */
            margin-top: 1rem;      /* Spazio sopra il bottone, se necessario */
        }

        .custom-close {
            color: white;
            background-color: #850404;
            border-color: #850404;
        }

    </style>
    <div class="row">
        <div class="col-lg-12">
            <!-- Card for Patient Info -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Patient: {{$patient->name." ".$patient->surname}}</h4>
                        <a href="{{ route('download.pdf', ['patientId' => $patient->id, 'csvId' => $csv->id] + (request('start_date') ? ['start_date' => request('start_date')] : []) + (request('end_date') ? ['end_date' => request('end_date')] : [])) }}"
                           class="btn btn-primary">Download PDF</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap">
                            <tbody>
                            <tr><td>Email:</td><td>{{$patient->email}}</td></tr>
                            <tr><td>Date of Birth:</td><td>{{ $patient->birth  ? \Carbon\Carbon::parse($patient->birth )->format('Y/m/d') : 'Not Specified' }}</td></tr>
                            <tr><td>Phone Number:</td><td>{{$patient->telephone_number}}</td></tr>
                            <tr><td>Address:</td><td>{{ $patient->address ?: 'Not Specified' }}</td></tr>
                            <tr>
                                <td>Analysis date:</td>
                                <td>
                                    From {{ $data['start_time'] ? \Carbon\Carbon::parse($data['start_time'])->format('Y/m/d') : 'Not Specified' }}
                                    to {{ $data['end_time'] ? \Carbon\Carbon::parse($data['end_time'])->format('Y/m/d') : 'Not Specified' }}
                                    @php
                                        $startTime = $data['start_time'] ? \Carbon\Carbon::parse($data['start_time'])->format('Y/m/d') : null;
                                        $endTime = $data['end_time'] ? \Carbon\Carbon::parse($data['end_time'])->format('Y/m/d') : null;
                                        $startDateFormatted = isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('Y/m/d') : null;
                                        $endDateFormatted = isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('Y/m/d') : null;
                                    @endphp
                                    @if(($startDateFormatted !== $startTime || $endDateFormatted !== $endTime) && $startDateFormatted && $endDateFormatted)
                                        <br>
                                        Filtered from {{ $startDateFormatted }} to {{ $endDateFormatted }}
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="container mt-4" id="scroll-to-form"> <!-- Aggiungi un ID qui -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center" style="font-size: 1.3rem;">Filter Analysis</h3>
                        <form id="date-form" action="{{ route('viewCsvRange', ['csvId' => $csv->id, 'patientId' => $patient->id]) }}" method="GET">
                            <div class="row" id="range">
                                <div class="col-sm-6 mb-3 mb-sm-0 daterange-container">
                                    <input type="text" name="daterange" value="" />
                                    <input type="hidden" id="start-date" name="start_date" />
                                    <input type="hidden" id="end-date" name="end_date" />
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-container mt-3">
                                @if(($startDateFormatted !== $startTime || $endDateFormatted !== $endTime) && $startDateFormatted && $endDateFormatted)
                                    <a href="{{ route('viewCsv', ['csvId' => $csv->id, 'patientId' => $patient->id]) }}" class="btn btn-secondary btn-center" style="background-color: #007bff; border-color: #007bff; color: white;">Reset Filter</a>
                                @endif
                            </div>
                        </form>
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
                                            <th>Action</th>
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
                                                <td>
                                                    <!-- Bottone per aprire il modale -->
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-{{ $loop->index }}">
                                                        View Details
                                                    </button>

                                                    <!-- Modale per visualizzare i dettagli -->
                                                    <div class="modal fade" id="modal-{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel-{{ $loop->index }}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalLabel-{{ $loop->index }}">Time Swings Details</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Tabella con i dettagli dei time swings -->
                                                                    <table class="table">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Day</th>
                                                                            <th>First event</th>
                                                                            <th>Second event</th>
                                                                            <th>Duration time swing</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach ($swing['Events'] as $event)
                                                                            <tr>
                                                                                <td>{{ $event['Day'] ?? 'N/A' }}</td>
                                                                                <td>{{ $event['First event'] ?? 'N/A' }}</td>
                                                                                <td>{{ $event['Second event'] ?? 'N/A' }}</td>
                                                                                <td>{{ $event['Duration time swing'] ?? 'N/A' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary custom-close" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Moment.js -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- DateRangePicker -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(function() {
            // Estrai le date dal backend e formattale in modo appropriato
            var startDate = "{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('m/d/Y') : ($data['start_time'] ? \Carbon\Carbon::parse($data['start_time'])->format('m/d/Y') : moment().startOf('month').format('m/d/Y')) }}";
            var endDate = "{{ $endDate ? \Carbon\Carbon::parse($endDate)->format('m/d/Y') : ($data['end_time'] ? \Carbon\Carbon::parse($data['end_time'])->format('m/d/Y') : moment().endOf('month').format('m/d/Y')) }}";
            var minDate = "{{ $data['start_time'] ? \Carbon\Carbon::parse($data['start_time'])->format('m/d/Y') : '' }}";
            var maxDate = "{{ $data['end_time'] ? \Carbon\Carbon::parse($data['end_time'])->format('m/d/Y') : '' }}";

            // Log per verificare se i dati sono corretti
            console.log("Start Date:", startDate);
            console.log("End Date:", endDate);

            // Configura il daterangepicker
            $('input[name="daterange"]').daterangepicker({
                startDate: startDate,
                endDate: endDate,
                minDate: minDate,
                maxDate: maxDate,
                opens: 'right',
                locale: {
                    format: 'MM/DD/YYYY' // Formato della data
                }
            }, function(start, end, label) {
                // Imposta i valori dei campi nascosti
                $('#start-date').val(start.format('YYYY-MM-DD'));
                $('#end-date').val(end.format('YYYY-MM-DD'));

                // Costruisci il range in formato 'YYYY-MM-DD - YYYY-MM-DD'
                var range = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');

                // Imposta il parametro range nella query string
                var form = $('#date-form'); // Assicurati di usare l'ID corretto del form
                var action = form.attr('action');
                form.attr('action', action.split('?')[0] + '?range=' + encodeURIComponent(range));

                // Invia il form
                form.submit();
            });
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

    <script>
        // Funzione per lo scroll lento
        function slowScrollTo(element, duration) {
            var targetPosition = element.getBoundingClientRect().top;
            var startPosition = window.pageYOffset;
            var startTime = null;

            function animation(currentTime) {
                if (startTime === null) startTime = currentTime;
                var timeElapsed = currentTime - startTime;
                var run = ease(timeElapsed, startPosition, targetPosition, duration);
                window.scrollTo(0, run);
                if (timeElapsed < duration) requestAnimationFrame(animation);
            }

            // Funzione di easing per rendere lo scroll più naturale
            function ease(t, b, c, d) {
                t /= d / 2;
                if (t < 1) return c / 2 * t * t + b;
                t--;
                return -c / 2 * (t * (t - 2) - 1) + b;
            }

            requestAnimationFrame(animation);
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Controlla se l'URL contiene parametri indicativi dell'invio del form
            if (window.location.search.includes('start_date') || window.location.search.includes('end_date')) {
                var element = document.getElementById("scroll-to-form");
                if (element) {
                    slowScrollTo(element, 1500); // Imposta la durata dello scroll (in millisecondi), ad esempio 1500ms
                }
            }
        });
    </script>




@endsection

