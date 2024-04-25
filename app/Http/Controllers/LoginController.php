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
            return redirect()->route('inicio');
        }
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'clave' => ['required', 'clave'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('inicio');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
