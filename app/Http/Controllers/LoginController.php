<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Login\LoginClass;
use Symfony\Component\VarDumper\VarDumper;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Login\LoginClass;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        // Si no existe sesión de Usuario
        if (!$request->has('usuario') && !$request->has('tipo')) {
            $clienteLogin = new LoginClass();
            $clienteLogin->autentificar();

            // return $clienteLogin->mensaje;
        }
        // Si existe la Sesión de Usuario.
        else {
            $usuario_test = $request->input('usuario');
            $tipo_test = $request->input('tipo');
            return redirect()->route('inicio', [
                'user' => $request->input('usuario'),
                'type' => $request->input('tipo')
            ]);
        }
    }
}
