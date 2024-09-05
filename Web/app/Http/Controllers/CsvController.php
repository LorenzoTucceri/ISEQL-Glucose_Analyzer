<?php

namespace App\Http\Controllers;


use App\Models\File;
use App\Models\Patient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class CsvController extends Controller
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

    public function storeCsv(Request $request)
    {
        $request->validate([
            'csv.*' => 'required|file|mimes:csv,txt'
        ]);

        try {
            if ($request->hasFile('csv')) {
                foreach ($request->file('csv') as $csvFile) {
                    // Genera un nome file unico per evitare conflitti
                    $csvFileName = time() . '_' . $csvFile->getClientOriginalName();

                    // Salva il file nella directory
                    $csvFile->storeAs('patients_csv/'. $request->get('patient_id').'/', $csvFileName);

                    // Crea il record nella tabella File
                    File::create([
                        'patient_id' => $request->get('patient_id'), // Associa il CSV a un paziente
                        'csv_file_path' => $csvFileName,
                        'start_time' => now(), // Puoi usare i valori che desideri per start_time e end_time
                        'end_time' => null,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'CSV file(s) uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error uploading CSV: ' . $e->getMessage()]);
        }
    }


    public function viewCsv($id, $patientId)
    {
        try {
            // Fetch patient data from the database
            $patient = Patient::find($patientId);

            $csv = File::find($id);

            // Check if the patient record exists
            if (!$patient) {
                return redirect()->back()->withErrors(['error' => 'Patient not found']);
            }

            // Prepare the CSV file path
            $csvFilePath = storage_path('app/patients_csv/'.$patient->id.'/'. $csv->csv_file_path);

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

                return view('patientDetails', compact('patient', 'data'));

            } catch (RequestException $e) {
                Log::error('HTTP Request Exception: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Failed file CSV probably has not the correct format.']);
            } catch (TransferException $e) {
                Log::error('HTTP Transfer Exception: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Network error while communicating with the Flask application']);
            }
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred']);
        }
    }

    public function deleteCsv(Request $request)
    {
        try {
            $file = File::find($request->csv_id);

            if (!$file) {
                return redirect()->back()->withErrors(['error' => 'File not found.']);
            }

            Storage::delete('patients_csv/'.$file->patient_id.'/'. $file->csv_file_path);
            $file->delete();

            return redirect()->back()->with('success', 'CSV file deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error deleting CSV: ' . $e->getMessage()]);
        }
    }




}
