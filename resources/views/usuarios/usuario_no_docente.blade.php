@extends('layouts.no_acceso_layout')

@section('title', 'Usuario no docente')

@section('content')
    <div style="margin-top: 2rem">
        <h1>Este usuario no es un docente</h1>
        <p style="margin-top: 1rem">
            Solo los docentes de la facultad de enfermería y nutrición pueden agendar áreas.
        </p>
        <a href="{{ url('/') }}" class="btn btn-info btn-sm fs-6">Regresar</a>
    </div>
@endsection