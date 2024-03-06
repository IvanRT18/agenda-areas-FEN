<?php

use App\Http\Controllers\AuditorioCitasController;
use App\Http\Controllers\LaboratorioCitasController;
use App\Http\Controllers\LoginController;
use App\Login\login_client;
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
    // var_dump('dentro de la página inicio');
    return view('home');
});

Route::post('guarda-auditorio-cita', [LaboratorioCitasController::class, 'store']);

Route::get('/horas-agendadas/{fecha}', [LaboratorioCitasController::class, 'horasAgendadas']);

