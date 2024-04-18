<?php

use App\Http\Controllers\AuditorioCitasController;
use App\Http\Controllers\DiaDeshabilitadoController;
use App\Http\Controllers\LaboratorioCitasController;
use App\Http\Controllers\LoginController;
use App\Login\login_client;
use App\Models\DiaDeshabilitado;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index']);

Route::get('admin-reservas-laboratorio', [LaboratorioCitasController::class, 'showAll']);

Route::get('reservas-laboratorio', [LaboratorioCitasController::class, 'showHorasDia']);

Route::get('index', [LoginController::class, 'index']);

Route::get('inicio', function(){
    return view('home');
})->name('inicio');

Route::post('guarda-auditorio-cita', [LaboratorioCitasController::class, 'store']);

Route::get('/horas-agendadas/{fecha}', [LaboratorioCitasController::class, 'horasAgendadas']);

Route::get('/edita-cita-laboratorio/{id}', [LaboratorioCitasController::class, 'editaCita']);

Route::put('/update-cita-laboratoria/{id}', [LaboratorioCitasController::class, 'updateCita']);

Route::get('borra-cita-laboratorio/{id}', [LaboratorioCitasController::class, 'borrarCita']);

Route::get('dias-inhabilitados-laboratorio', [DiaDeshabilitadoController::class, 'index']);

Route::post('agrega-dia-inhabilitado-laboratorio', [DiaDeshabilitadoController::class, 'store']);

Route::get('all-dias-inhabilitados-laboratorio', [DiaDeshabilitadoController::class, 'allDiasDeshabilitados']);

Route::get('elimina-dia-deshabilitado-laboratorio/{id}', [DiaDeshabilitadoController::class, 'eliminaDiaDeshabilitado']);

Route::get('elimina-all-dias-deshabilitados-laboratorio/', [DiaDeshabilitadoController::class, 'eliminaAllDiasDeshabilitados']);

