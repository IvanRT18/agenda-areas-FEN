<?php

use App\Http\Controllers\AuditorioCitasController;
use App\Http\Controllers\DiaDeshabilitadoController;
use App\Http\Controllers\AreasControllers\LaboratorioCitasController;
use App\Http\Controllers\AreasControllers\AuditorioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistraUsuariosController;
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

//Ruta que se ingresa al acceder a la direccion del servidor
Route::get('/', [LoginController::class, 'index'])->name('index');

Route::group(['middleware'=>['UsuarioAutenticado']], function(){
    //Ruta del administrador
    Route::get('administrador', [LaboratorioCitasController::class, 'showAll']);
    
    //Ruta de la pagina de inicio
    Route::get('inicio', function(){
        return view('home');
    })->name('inicio');
    
    //Ruta para ver las horas reservadas
    Route::prefix('horas-reservadas')->group(function () {
        Route::get('/', function () {
            // Redirije al usuario a las horas agendadas del laboratorio por default
            return redirect()->route('laboratorio');
        });

        Route::prefix('laboratorio')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [LaboratorioCitasController::class, 'showHorasDia'])->name('laboratorio');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [LaboratorioCitasController::class, 'horasAgendadas'])->name('reserva-por-fecha');
        });
    
        Route::prefix('aula-magna')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [LaboratorioCitasController::class, 'showHorasDia'])->name('aula-magna');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [LaboratorioCitasController::class, 'horasAgendadas'])->name('reserva-por-fecha');
        });

        Route::prefix('auditorio')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [AuditorioController::class, 'showHorasDia'])->name('auditorio');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [AuditorioController::class, 'horasAgendadas'])->name('reserva-por-fecha');
        });

        Route::prefix('sala-videoconferencias')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [LaboratorioCitasController::class, 'showHorasDia'])->name('sala-videoconferencias');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [LaboratorioCitasController::class, 'horasAgendadas'])->name('reserva-por-fecha');
        });
    });

    //Rutas para registrar reservaciones de áreas
    Route::prefix('registra-reservacion')->group(function(){
        Route::post('auditorio', [AuditorioController::class, 'store']);
        Route::post('aula-magna', [AuditorioController::class, 'store']);
        Route::post('laboratorio', [LaboratorioCitasController::class, 'store']);
        Route::post('sala-videoconferencias', [AuditorioController::class, 'store']);
    });
});

//Cierra la sesión del usuario
Route::get(('/logout'), function(){
    session()->forget('user');
    session()->forget('type');
	setcookie("SC","",0);
    session()->invalidate();
    session()->regenerate();
    return redirect('index');
});



Route::get('index', [LoginController::class, 'index']);
Route::get('/public/index', [LoginController::class, 'index']);
Route::get('public/index', [LoginController::class, 'index']);

Route::get('/horas-agendadas/{fecha}', [LaboratorioCitasController::class, 'horasAgendadas']);

Route::get('/edita-cita-laboratorio/{id}', [LaboratorioCitasController::class, 'editaCita']);

Route::put('/update-cita-laboratoria/{id}', [LaboratorioCitasController::class, 'updateCita']);

Route::get('borra-cita-laboratorio/{id}', [LaboratorioCitasController::class, 'borrarCita']);

Route::post('agrega-dia-inhabilitado-laboratorio', [DiaDeshabilitadoController::class, 'store']);

Route::get('all-dias-inhabilitados-laboratorio', [DiaDeshabilitadoController::class, 'allDiasDeshabilitados']);

Route::get('dias-inhabilitados-laboratorio', [DiaDeshabilitadoController::class, 'index']);

Route::get('elimina-dia-deshabilitado-laboratorio/{id}', [DiaDeshabilitadoController::class, 'eliminaDiaDeshabilitado']);

Route::get('elimina-all-dias-deshabilitados-laboratorio/', [DiaDeshabilitadoController::class, 'eliminaAllDiasDeshabilitados']);

Route::get('registrar-usuario', [RegistraUsuariosController::class, 'index']);

Route::post('registrar-usuario', [RegistraUsuariosController::class, 'store']);