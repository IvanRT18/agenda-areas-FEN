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
        // Si no existe sesión de Usuario
        if (!$request->has('usuario') && !$request->has('tipo')) {
            $clienteLogin = new LoginClass();
            $clienteLogin->autentificar();
        }
        // Si existe la Sesión de Usuario.
        else {
            session()->put('user', $request->input('usuario'));
            session()->put('type', $request->input('tipo'));
            $usuario = Usuario::where('clave', session('user'))->first();
            if ($usuario) {
                Auth::loginUsingId($usuario->clave);
                return redirect(RouteServiceProvider::INICIO);
            }
            else{
                return redirect('usuario-no-registrado');
            }
        }
    }
}
