<?php

use App\Http\Controllers\AuditorioCitasController;
use App\Http\Controllers\DiaDeshabilitadoController;
use App\Http\Controllers\AreasControllers\LaboratorioController;
use App\Http\Controllers\AreasControllers\AuditorioController;
use App\Http\Controllers\AreasControllers\AulaMagnaController;
use App\Http\Controllers\AreasControllers\SalaVideoconferenciaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuariosController;
use App\Login\login_client;
use App\Mail\AreaReservadaMail;
use App\Models\DiaDeshabilitado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

Route::group(['middleware'=>['auth']], function(){
    Route::get('obtener-dias-deshabilitados', [DiaDeshabilitadoController::class, 'getAllDisabledDays']);

    //Ruta del administrador
    Route::get('administrador', [LaboratorioController::class, 'showAll']);
    
    //Ruta de la pagina de inicio
    Route::get('inicio', function(){
        return view('home');
    })->name('inicio');
    
    //Rutas para ver las horas reservadas
    Route::prefix('horas-reservadas')->group(function () {
        Route::get('/', function () {
            // Redirije al usuario a las horas agendadas del laboratorio por default
            return redirect()->route('laboratorio');
        });

        Route::prefix('laboratorio')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [LaboratorioController::class, 'showHorasDia'])->name('laboratorio');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha} 
            Route::get('{fecha}', [LaboratorioController::class, 'horasAgendadas'])->name('reserva-por-fecha');
            // Route::get('adelante/{fecha}', [LaboratorioController::class, 'horasAgendadasAdelante'])->name('reserva-por-fecha');
            // Route::get('atras/{fecha}', [LaboratorioController::class, 'horasAgendadasAtras'])->name('reserva-por-fecha');

            // Ruta para cuando saber solo las horas reservadas del día en laboratorio
            // Route::get('disabled/{fecha}', [LaboratorioController::class, 'horasDisabled']);
        });
    
        Route::prefix('aula-magna')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [AulaMagnaController::class, 'showHorasDia'])->name('aula-magna');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [AulaMagnaController::class, 'horasAgendadas'])->name('reserva-por-fecha');
        });

        Route::prefix('auditorio')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [AuditorioController::class, 'showHorasDia'])->name('auditorio');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [AuditorioController::class, 'horasAgendadas'])->name('reserva-por-fecha');
        });

        Route::prefix('sala-videoconferencias')->group(function () {
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio
            Route::get('/', [SalaVideoconferenciaController::class, 'showHorasDia'])->name('sala-videoconferencias');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [SalaVideoconferenciaController::class, 'horasAgendadas'])->name('reserva-por-fecha');
        });
    });

    //Rutas de administrador
    Route::middleware('admin')->prefix('administrador')->group(function(){
        Route::get('/', [LaboratorioController::class, 'showAll']);

        //Muestra las horas reservadas en una tabla con scroll automatico
        Route::prefix('reservaciones')->group(function(){
            Route::get('/', [LaboratorioController::class, 'showAll']); //Por defecto, al entrar al panel de administrador carga las reservacion del laboratorio
            Route::get('auditorio/', [AuditorioController::class, 'showAll']);
            Route::get('aula-magna/', [AulaMagnaController::class, 'showAll']);
            Route::get('laboratorio/', [LaboratorioController::class, 'showAll']);
            Route::get('sala-videoconferencias/', [SalaVideoconferenciaController::class, 'showAll']);
        });

        //Registro de usuarios
        Route::prefix('usuarios')->group(function(){
            Route::get('/', [UsuariosController::class, 'showUsers']);
            Route::get('registrar', [UsuariosController::class, 'index']);
            Route::post('registrar', [UsuariosController::class, 'store']);
            Route::get('editar/{id}', [UsuariosController::class, 'edit']);
            Route::put('actualizar/{id}', [UsuariosController::class, 'update']);
            Route::get('eliminar/{id}', [UsuariosController::class, 'delete']);
        });

        //Rutas para registrar reservaciones de áreas
        Route::prefix('registra-reservacion')->group(function(){
            Route::post('auditorio', [AuditorioController::class, 'store']);
            Route::post('aula-magna', [AulaMagnaController::class, 'store']);
            Route::post('laboratorio', [LaboratorioController::class, 'store']);
            Route::post('sala-videoconferencias', [SalaVideoconferenciaController::class, 'store']);
        });

        //Rutas para editar reservaciones de áreas
        Route::prefix('editar-reservacion')->group(function(){
            Route::get('auditorio/{id}', [AuditorioController::class, 'edit']);
            Route::get('aula-magna/{id}', [AulaMagnaController::class, 'edit']);
            Route::get('laboratorio/{id}', [LaboratorioController::class, 'edit']);
            Route::get('sala-videoconferencias/{id}', [SalaVideoconferenciaController::class, 'edit']);
        });

        //Rutas para actualizar reservaciones de áreas
        Route::prefix('actualizar-reservacion')->group(function(){
            Route::put('auditorio/{id}', [AuditorioController::class, 'update']);
            Route::put('aula-magna/{id}', [AulaMagnaController::class, 'update']);
            Route::put('laboratorio/{id}', [LaboratorioController::class, 'update']);
            Route::put('sala-videoconferencias/{id}', [SalaVideoconferenciaController::class, 'update']);
        });

        //Rutas para borrar reservaciones
        Route::prefix('borrar-reservacion')->group(function(){
            Route::get('/', [LaboratorioController::class, 'showAll']);
            Route::get('auditorio/{id}', [AuditorioController::class, 'delete']);
            Route::get('aula-magna/{id}', [AulaMagnaController::class, 'delete']);
            Route::get('laboratorio/{id}', [LaboratorioController::class, 'delete']);
            Route::get('sala-videoconferencias/{id}', [SalaVideoconferenciaController::class, 'delete']);
        });

        //Rutas deshabilitar días
         Route::prefix('deshabilitar-dias')->group(function(){
            Route::get('/', [DiaDeshabilitadoController::class, 'index']);
            Route::post('agregar-dia', [DiaDeshabilitadoController::class, 'store']);
            Route::get('habilita-dia/{id}', [DiaDeshabilitadoController::class, 'habilitaDia']);
            Route::get('habilita-todos-dias', [DiaDeshabilitadoController::class, 'habilitaTodosDias']);
            Route::get('obtener-dias-deshabilitados', [DiaDeshabilitadoController::class, 'getAllDisabledDays']);
        });

        //Rutas para configurar las áreas
        Route::prefix('actualizar-area')->group(function(){
            Route::put('auditorio', [AuditorioController::class, 'updateConfiguration']);
            Route::put('aula-magna', [AulaMagnaController::class, 'updateConfiguration']);
            Route::put('laboratorio', [LaboratorioController::class, 'updateConfiguration']);
            Route::put('sala-videoconferencias', [SalaVideoconferenciaController::class, 'updateConfiguration']);
        });

        // //Rutas para configurar las áreas
        Route::prefix('configurar-area')->group(function(){
            Route::get('auditorio', [AuditorioController::class, 'configure']);
            Route::get('aula-magna', [AulaMagnaController::class, 'configure']);
            Route::get('laboratorio', [LaboratorioController::class, 'configure']);
            Route::get('sala-videoconferencias', [SalaVideoconferenciaController::class, 'configure']);
        });
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

Route::get('index', [LoginController::class, 'index'])->name('login');

Route::get('/public/index', [LoginController::class, 'index']);

Route::get('public/index', [LoginController::class, 'index']);

Route::get('/horas-reservadas/{fecha}', [LaboratorioController::class, 'horasAgendadas']);

Route::get('usuario-no-registrado', [UsuariosController::class, 'isUserRegistered'])->name('no-access');

Route::get('usuario-no-admin', [UsuariosController::class, 'isUserAdmin'])->name('no-admin');