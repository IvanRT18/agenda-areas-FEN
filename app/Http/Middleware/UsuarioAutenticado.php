<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioAutenticado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Si no existe un usuario en la sesión
        if (!session()->has('user') && !session()->has('type')) {
            return redirect('index');
        }
        //Sí hay usuario en la sesión
        else{
            // $usuario = Usuario::where('clave', session('user'))->first();
            // if ($usuario) {
            //     return $next($request);
            // }
            // else{
            //     return redirect('home');
            // }
            return $next($request);
        }
    }
}
