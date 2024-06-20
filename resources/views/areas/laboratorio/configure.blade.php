@extends('layouts.administrador_layout')

@section('title', 'Configurar área - Laboratorio')

@section('table')
<div class="card">
    <div class="card-header">
        <h4>Configuración del laboratorio
            <a href="/administrador/reservaciones/laboratorio" class="btn btn-danger float-end">Regresar</a>
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ url('administrador/actualizar-area/laboratorio') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="">Número de computadoras</label>
                <input type="text" name="numero_computadoras" value="{{$configuracion->numero_computadoras ?? 0}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar reservación</button>
            </div>
        </form>
    </div>
</div>

@endsection