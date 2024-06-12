@extends('layouts.editar_reservacion_layout')

@section('title', 'Editar usuario')

@section('form-update')
    <div style="margin-top: 2rem">
        <h1>Usuario no registrado</h1>
        <p style="margin-top: 1rem">
            Este usuario no esta registrado para reservar áreas. Por favor, contacte con el departamento de infórmatica de la facultad.
        </p>
        <a href="{{ url('/') }}" class="btn btn-info btn-sm fs-6">Regresar</a>
    </div>
@endsection