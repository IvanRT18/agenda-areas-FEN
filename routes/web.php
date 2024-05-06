<?php

use App\Http\Controllers\AuditorioCitasController;
use App\Http\Controllers\DiaDeshabilitadoController;
use App\Http\Controllers\AreasControllers\LaboratorioController;
use App\Http\Controllers\AreasControllers\AuditorioController;
use App\Http\Controllers\AreasControllers\AulaMagnaController;
use App\Http\Controllers\AreasControllers\SalaVideoconferenciaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistraUsuariosController;
use App\Login\login_client;
use App\Mail\AreaReservadaMail;
use App\Models\DiaDeshabilitado;
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

Route::group(['middleware'=>['UsuarioAutenticado']], function(){
    //Ruta del administrador
    Route::get('administrador', [LaboratorioController::class, 'showAll']);
    
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
            Route::get('/', [LaboratorioController::class, 'showHorasDia'])->name('laboratorio');
    
            // Ruta para cuando el usuario accede a horas-reservadas/laboratorio/{fecha}
            Route::get('{fecha}', [LaboratorioController::class, 'horasAgendadas'])->name('reserva-por-fecha');
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

    //Rutas para registrar reservaciones de áreas
    Route::prefix('registra-reservacion')->group(function(){
        Route::post('auditorio', [AuditorioController::class, 'store']);
        Route::post('aula-magna', [AulaMagnaController::class, 'store']);
        Route::post('laboratorio', [LaboratorioController::class, 'store']);
        Route::post('sala-videoconferencias', [SalaVideoconferenciaController::class, 'store']);
    });

     //Rutas para actualizar reservaciones de áreas
     Route::prefix('actualizar-reservacion')->group(function(){
        Route::put('auditorio/{id}', [AuditorioController::class, 'update']);
        Route::put('aula-magna/{id}', [AulaMagnaController::class, 'update']);
        Route::put('laboratorio/{id}', [LaboratorioController::class, 'update']);
        Route::put('sala-videoconferencias/{id}', [SalaVideoconferenciaController::class, 'update']);
    });

    //Rutas para editar reservaciones de áreas
    Route::prefix('editar-reservacion')->group(function(){
        Route::get('auditorio/{id}', [AuditorioController::class, 'edit']);
        Route::get('aula-magna/{id}', [AulaMagnaController::class, 'edit']);
        Route::get('laboratorio/{id}', [LaboratorioController::class, 'edit']);
        Route::get('sala-videoconferencias/{id}', [SalaVideoconferenciaController::class, 'edit']);
    });

    //Rutas para ver las reservaciones de las áreas
    Route::prefix('administrador')->group(function(){
        Route::get('/', [LaboratorioController::class, 'showAll']);
        Route::prefix('reservaciones')->group(function(){
            Route::get('/', [LaboratorioController::class, 'showAll']);
            Route::get('auditorio/', [AuditorioController::class, 'showAll']);
            Route::get('aula-magna/', [AulaMagnaController::class, 'showAll']);
            Route::get('laboratorio/', [LaboratorioController::class, 'showAll']);
            Route::get('sala-videoconferencias/', [SalaVideoconferenciaController::class, 'showAll']);
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



Route::get('index', [LoginController::class, 'index']);
Route::get('/public/index', [LoginController::class, 'index']);
Route::get('public/index', [LoginController::class, 'index']);

Route::get('/horas-reservadas/{fecha}', [LaboratorioController::class, 'horasAgendadas']);

// Route::get('edita-cita-laboratorio/{id}', [LaboratorioController::class, 'edit']);

// Route::put('update-cita-laboratoria/{id}', [LaboratorioController::class, 'update']);

Route::get('borra-cita-laboratorio/{id}', [LaboratorioController::class, 'borrarCita']);

Route::post('agrega-dia-inhabilitado-laboratorio', [DiaDeshabilitadoController::class, 'store']);

Route::get('all-dias-inhabilitados-laboratorio', [DiaDeshabilitadoController::class, 'allDiasDeshabilitados']);

Route::get('dias-inhabilitados-laboratorio', [DiaDeshabilitadoController::class, 'index']);

Route::get('elimina-dia-deshabilitado-laboratorio/{id}', [DiaDeshabilitadoController::class, 'eliminaDiaDeshabilitado']);

Route::get('elimina-all-dias-deshabilitados-laboratorio/', [DiaDeshabilitadoController::class, 'eliminaAllDiasDeshabilitados']);

Route::get('registrar-usuario', [RegistraUsuariosController::class, 'index']);

Route::post('registrar-usuario', [RegistraUsuariosController::class, 'store']);

Route::get('email', function () {
    $correo = new AreaReservadaMail;
    Mail::to('ivan18rt@outlook.com')->send($correo);
    return "Mensaje enviado.";
    // return view('home');
})->name('contactanos.index');