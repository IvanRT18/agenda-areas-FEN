<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class RegistraUsuariosController extends Controller
{
    public function index(){
        return view('registrar_usuario');
    }

    //Registra un nuevo usuario a la base de datos
    public function store(Request $request){
        // Validar si el correo ya está registrado
        $existingUser = Usuario::where('correo', $request->correo)->first();
        // Validar si la clave ya está registrada
        $existingClave = Usuario::where('clave', $request->clave)->first();

        // Definir los mensajes de error
        $errors = [
            'correo' => $existingUser ? 'El correo electrónico ya está registrado' : null,
            'clave' => $existingClave ? 'El RPE ya está registrado' : null,
        ];

        // Redireccionar con los errores si es necesario
        if ($existingUser || $existingClave) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // Crear el nuevo usuario
        $user = new Usuario();
        $user->clave = $request->clave;
        $user->apellido_materno = $request->apellido_materno;
        $user->apellido_paterno = $request->apellido_paterno;
        $user->nombres = $request->nombres;
        $user->tipo = $request->tipo;
        $user->correo = $request->correo;
        $user->save();

        return redirect()->back()->with('status', 'Se ha registrado al nuevo usuario correctamente');
    }
}
