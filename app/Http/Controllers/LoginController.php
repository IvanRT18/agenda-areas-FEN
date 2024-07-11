<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Login\LoginClass;
use Symfony\Component\VarDumper\VarDumper;
use Illuminate\Session\store;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Login\LoginClass;
use App\Models\Usuario;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        // Si no existe sesión de usuario activa
        if (!$request->has('usuario') && !$request->has('tipo')) {
            $clienteLogin = new LoginClass(); //Se autentifica con el login de la universidad
            $clienteLogin->autentificar();
        }
        // Sí el usuario ya inicio sesión con el login de la universidad
        else {
            session()->put('user', $request->input('usuario'));
            session()->put('type', $request->input('tipo'));
            // dd(session('type'));
            //Si no es alumno, accede a la página
            // if (session('type') !== 'ALUMNOS_Alumno') {
                $usuario = Usuario::where('clave', session('user'))->first();
                if ($usuario) {
                    Auth::loginUsingId($usuario->clave);
                    return redirect(RouteServiceProvider::INICIO);
                }
                else{
                    return redirect(route('no-access'));
                }
            // }
            // else{
            //     return redirect(route('no-docente'));
            // }
        }
    }
}
