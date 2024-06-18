@extends('layouts.no_acceso_layout')

@section('title', 'Usuario no administrador')

@section('content')
    <div style="margin-top: 2rem">
        <h1>Este usuario no es administrador.</h1>
        <p style="margin-top: 1rem">
            No cuentas con los permisos necesarios para realizar esta acción.
        </p>
        <a href="{{ url('inicio') }}" class="btn btn-info btn-sm fs-6">Regresar</a>
    </div>
@endsection