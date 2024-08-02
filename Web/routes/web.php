<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();




//Creare dei middleware che permettano l'accesso solo agli admin,operatori
// e alle imprese possesori di quei file;
//aggiungere condizione per l'impresa
//aggiustare url completo per le sottocartelle






Route::get('/', [App\Http\Controllers\UserController::class, 'root'])->name('root');

Route::get('{any}', [App\Http\Controllers\UserController::class, 'index'])->name('index');

Route::post('/resetPassword', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('resetPassword');

Route::view('/userManagement', "userManagement")->name("userManagement");
Route::view('/userProfile', "userProfile")->name("userProfile");

//Profile
Route::post('/update-profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('updateProfile');
Route::post('/new-profile', [App\Http\Controllers\UserController::class, 'newProfile'])->name('newProfile');
Route::post('/update-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('updatePassword');

//User
Route::get('/searchUser/{id}', [App\Http\Controllers\UserController::class, 'searchUser'])->name('searchUser');
Route::post('/deleteUser', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('deleteUser');
Route::post('/updateUser', [App\Http\Controllers\UserController::class, 'updateUser'])->name('updateUser');


Route::view("/patientManagement", "patientManagement")->name("patientManagement");
Route::post('/deletePatient', [App\Http\Controllers\PatientController::class, 'deletePatient'])->name('deletePatient');
Route::view("/newPatient", "newPatient")->name("newPatient");
Route::post("/addPatient", [App\Http\Controllers\PatientController::class, 'addPatient'])->name("addPatient");
Route::get("/searchPatient/{id}", [App\Http\Controllers\PatientController::class, 'searchPatient'])->name("searchPatient");
Route::post("/updatePatient", [App\Http\Controllers\PatientController::class, 'updatePatient'])->name("updatePatient");

Route::post("/updatePatient", [App\Http\Controllers\PatientController::class, 'updatePatient'])->name("updatePatient");
Route::get('patients/{id}/details', [App\Http\Controllers\PatientController::class, 'showPatientDetails'])->name('detailsPatient');
Route::get('/patient/{clientId}/download-pdf', [App\Http\Controllers\PatientController::class, 'downloadPDF'])->name('download.pdf');
