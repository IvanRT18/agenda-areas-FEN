@extends('layouts.administrador_layout')

@section('title', 'Configurar área - Sala videoconferencias')

@section('table')
<div class="card mt-3">
    <div class="card-header">
        <h4>Configuración de sala videoconferencias
            <a href="/administrador/reservaciones/auditorio" class="btn btn-danger float-end">Regresar</a>
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ url('administrador/actualizar-area/sala-videoconferencias') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="">Número microfonos</label>
                <input type="text" name="numero_microfonos" value="{{ $configuracion->numero_microfonos ?? 0 }}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar reservación</button>
            </div>
        </form>
    </div>
</div>

@endsection