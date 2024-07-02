@extends('layouts.no_acceso_layout')

@section('title', 'Página no encontrada')

@section('content')
    <div style="margin-top: 2rem">
        <h1>Error 404</h1>
        <p style="margin-top: 1rem">
            La página que estas tratando de acceder no ha sido encontrada, revisa la que la URL sea correcta.
        </p>
        <a href="{{ url('/') }}" class="btn btn-info btn-sm fs-6">Regresar</a>
    </div>
@endsection