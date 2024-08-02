<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Database\QueryException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    public function root()
    {
        return view('index');
    }

    public function updatePatient(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'birth' => ['required', 'string', 'max:255'],
            'telephone_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'csv' => ['nullable', 'file', 'mimes:csv,txt']
        ]);

        DB::beginTransaction();

        try {
            $patient = Patient::findOrFail($request->client_id);

            if ($request->hasFile('csv')) {
                $file = $request->file('csv');
                $csvFileName = $request->client_id . '_' . time() . '_' . $file->getClientOriginalName();
                $file->storeAs('patients_csv', $csvFileName);

                $patient->csv_file_path = $csvFileName;
            }

            $patient->update($request->only(['name', 'surname', 'email', 'birth', 'telephone_number', 'address', 'csv_file_path']));

            DB::commit();
            return redirect()->route('searchPatient', $request->client_id)->with('success', 'Patient information updated successfully!');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Patient not found.']);
        } catch (QueryException $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error updating patient: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }


    public function addPatient(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'birth' => ['required', 'string', 'max:255'],
            'telephone_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'csv' => ['required', 'file', 'mimes:csv,txt']
        ]);

        DB::beginTransaction();

        try {

            $patient = Patient::create([
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'email' => $request->get('email'),
                'birth' => $request->get('birth'),
                'telephone_number' => $request->get('telephone_number'),
                'address' => $request->get('address'),
                'csv_file_path' => null
            ]);

            $file = $request->file('csv');
            $csvFileName = $patient->id . '_' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('patients_csv', $csvFileName);

            $patient->csv_file_path = $csvFileName;

            $patient->update();




            DB::commit();
            return back()->with('success', 'Patient added successfully!');
        } catch (QueryException $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error adding patient: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }


    public function searchPatient($id)
    {
        try {
            $client = Patient::findOrFail($id);
            return view('updatePatient', ['client' => $client]);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Patient not found.']);
        }
    }



    public function showPatientDetails($id)
    {
        try {
            // Fetch patient data from the database
            $client = Patient::find($id);

            // Check if the patient record exists
            if (!$client) {
                return redirect()->back()->withErrors(['error' => 'Patient not found']);
            }

            // Prepare the CSV file path
            $csvFilePath = storage_path('app/patients_csv/' . $client->csv_file_path);

            // Check if the CSV file exists
            if (!file_exists($csvFilePath)) {
                return redirect()->back()->withErrors(['error' => 'CSV file not found']);
            }

            // Send request to Flask application
            $httpClient = new Client();
            try {
                $response = $httpClient->request('POST', 'http://127.0.0.1:5000/process-csv', [
                    'multipart' => [
                        [
                            'name'     => 'csv_file',
                            'contents' => fopen($csvFilePath, 'r'),
                        ],
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                #dd($data);


                // Check if JSON decoding was successful
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return redirect()->back()->withErrors(['error' => 'Failed to parse JSON response from the Flask application']);
                }

                //dd($data);

                return view('patientDetails', compact('client', 'data'));
            } catch (RequestException $e) {
                Log::error('HTTP Request Exception: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Failed file CSV probably has not the correct format.']);
            } catch (TransferException $e) {
                Log::error('HTTP Transfer Exception: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Network error while communicating with the Flask application']);
            }
        } catch (\Exception $e) {
            dd($e);
            Log::error('Unexpected Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred']);
        }
    }
    public function deletePatient(Request $request)
    {
        $request->validate(['patient' => ['required', 'exists:patients,id']]);

        try {
            $patient = Patient::findOrFail($request->patient);
            $patient->delete();
            return back()->with('success', 'Patient deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Patient not found.']);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Error deleting patient: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
    public function downloadPDF($clientId)
    {
        // Recupera i dati del paziente
        $client = Patient::findOrFail($clientId);

        // Prepara il percorso del file CSV
        $csvFilePath = storage_path('app/patients_csv/' . $client->csv_file_path);

        // Controlla se il file CSV esiste
        if (!file_exists($csvFilePath)) {
            abort(404, 'CSV file not found');
        }

        // Invia la richiesta all'applicazione Flask
        $httpClient = new Client();
        $response = $httpClient->request('POST', 'http://127.0.0.1:5000/process-csv', [
            'multipart' => [
                [
                    'name' => 'csv_file',
                    'contents' => fopen($csvFilePath, 'r'),
                ],
            ],
        ]);

        // Decodifica la risposta JSON dall'API Flask
        $data = json_decode($response->getBody(), true);

        // Genera il PDF
        $pdf = PDF::loadView('pdf.patient-details', compact('client', 'data'));

        // Scarica il PDF
        return $pdf->download('patient-details-' . $clientId . '.pdf');
    }

}
