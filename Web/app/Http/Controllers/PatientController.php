<?php

namespace App\Http\Controllers;

use App\Models\File;
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
use Illuminate\Support\Facades\Storage;

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
        ]);

        DB::beginTransaction();

        try {
            $patient = Patient::findOrFail($request->client_id);


            $patient->update($request->only(['name', 'surname', 'email', 'birth', 'telephone_number', 'address']));

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
        ]);

        DB::beginTransaction();

        try {
            // Creazione del paziente
            $patient = Patient::create([
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'email' => $request->get('email'),
                'birth' => $request->get('birth'),
                'telephone_number' => $request->get('telephone_number'),
                'address' => $request->get('address')
            ]);


            DB::commit();

            return redirect()->route('showCsvPatient', $patient->id)
                ->with('success', 'Il paziente è stato aggiunto con successo!');
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


    public function deletePatient(Request $request)
    {
        $request->validate([
            'patient' => ['required', 'exists:patients,id'],
        ]);

        $patientId = $request->patient;

        try {
            // Recupera il paziente
            $patient = Patient::findOrFail($patientId);

            // Recupera i file associati al paziente
            $files = File::where('patient_id', $patientId)->get();
            foreach ($files as $file) {
                // Elimina il file fisico dal sistema
                $filePath = 'patients_csv/'.$patientId.'/'. $file->csv_file_path;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
                // Elimina il record del file dal database
                $file->delete();
            }


            // Elimina la directory se vuota
            $directory = 'patients_csv/' . $patientId;
            Storage::deleteDirectory($directory);


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
    public function downloadPDF($id, $patientId)
    {
        // Recupera i dati del paziente
        $client = Patient::findOrFail($patientId);
        $csv = File::find($id);

        // Prepara il percorso del file CSV
        $csvFilePath = storage_path('app/patients_csv/'.$patientId.'/'. $csv->csv_file_path);

        // Controlla se il file CSV esiste
        if (!file_exists($csvFilePath)) {
            abort(404, 'CSV file not found');
        }

        // Recupera i parametri opzionali start_date e end_date dalla richiesta
        $startDate = request('start_date');
        $endDate = request('end_date');

        // Crea l'array per inviare i parametri all'API Flask
        $multipart = [
            [
                'name' => 'csv_file',
                'contents' => fopen($csvFilePath, 'r'),
            ]
        ];

        // Aggiungi start_date e end_date se sono presenti
        if ($startDate) {
            $multipart[] = [
                'name' => 'start_date',
                'contents' => $startDate,
            ];
        }

        if ($endDate) {
            $multipart[] = [
                'name' => 'end_date',
                'contents' => $endDate,
            ];
        }

        // Invia la richiesta all'applicazione Flask
        $httpClient = new Client();
        $response = $httpClient->request('POST', 'http://127.0.0.1:5000/process-csv', [
            'multipart' => $multipart,
        ]);

        // Decodifica la risposta JSON dall'API Flask
        $data = json_decode($response->getBody(), true);

        // Genera il PDF utilizzando i dati ricevuti
        $pdf = PDF::loadView('pdf.patient-details', compact('client', 'data'));

        // Scarica il PDF
        return $pdf->download('patient-details-' . $patientId . '.pdf');
    }
    public function showCsvPatient($patientId)
    {
        try {
            #dd($patientId);
            $patient = Patient::find($patientId);
            $files = File::where("patient_id", $patientId)->orderBy("id")->get();

            return view('csvPatient', compact('files', 'patient'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore durante il recupero dei file CSV: ' . $e->getMessage()]);
        }
    }


}
