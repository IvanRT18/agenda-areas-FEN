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
