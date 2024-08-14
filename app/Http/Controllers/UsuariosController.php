<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UsuariosController extends Controller
{
    //Vista para registrar usuarios
    public function index(){
        return view('usuarios.registrar_usuario');
    }

    //Guarda un nuevo usuario a la base de datos
    public function store(Request $request)
    {
        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'clave' => 'required|integer|digits_between:1,9',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'nombres' => 'required|string|max:30',
            'tipo' => 'required|string',
            'correo' => 'required|email',
        ]);

         // Si hay errores, lanzar una excepción de validación
         if ($validator->fails()) {
            throw new ValidationException($validator);
        }

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

    //Muestra a todos los usuarios registrados en el sistema
    public function showUsers(Request $request){
        $usuarios = Usuario::all();
        return view('usuarios.ver_usuarios', compact('usuarios'));
    }
    
    // Vista para editar usuario
    public function edit($id){
        $usuarioEdit = Usuario::find($id);
        return view('usuarios.editar_usuario', compact('usuarioEdit'));
    }

    //Actualiza usuario
    public function update(Request $request, $id)
    {
         // Validación de datos de entrada
         $validator = Validator::make($request->all(), [
            'clave' => [
            'required',
            'integer',
            'digits_between:1,9',
            Rule::unique('usuarios')->ignore($id),
        ],
        'apellido_paterno' => 'required|string|max:30',
        'apellido_materno' => 'required|string|max:30',
        'nombres' => 'required|string|max:30',
        'tipo' => 'required|string',
        'correo' => [
            'required',
            'email',
            Rule::unique('usuarios')->ignore($id),
        ],
        ]);

         // Si hay errores, lanzar una excepción de validación
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        
        $usuario = Usuario::find($id);
        $usuario->clave = $request->input('clave');
        $usuario->apellido_materno = $request->input('apellido_materno');
        $usuario->apellido_paterno = $request->input('apellido_paterno');
        $usuario->nombres = $request->input('nombres');
        $usuario->tipo = $request->input('tipo');
        $usuario->correo = $request->input('correo');
        $usuario->update();
        return redirect()->back()->with('status','Usuario actualizado correctamente');
    }

    //Elimina a un usuario
    public function delete($id)
    {
        $usuario = Usuario::find($id);
        $usuario->delete();
        return redirect()->back()->with('status', 'Usuario borrado correctamente');
    }

    //Valida que el usuario este registrado en el sistema
    public function isUserRegistered(){
        if (Auth::check()) {
            return redirect(route('inicio'));
        }
        else{
            return view('usuarios.usuario_no_registrado'); //El usuario es valido, pero no esta registrado dentro sistema
        }
    }

    //Valida que el usuario sea un administrador
    public function isUserAdmin(){
        if (Auth::check() && auth()->user()->tipo === 'administrador') {
            return redirect(route('inicio'));
        }
        else{
            return view('usuarios.usuario_no_admin'); //Vista de error
        }
    }

    //Muestra vista de que el usuario que esta ingresando no es un docente
    public function noDocente(){
        return view('usuarios.usuario_no_docente');
    }
}
