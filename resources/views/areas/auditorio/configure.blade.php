@extends('layouts.administrador_layout')

@section('title', 'Configurar área - Auditorio')

@section('table')
<div class="card">
    <div class="card-header">
        <h4>Configuración del auditorio
            <a href="/administrador/reservaciones/auditorio" class="btn btn-danger float-end">Regresar</a>
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ url('administrador/actualizar-area/auditorio') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="">Número microfonos</label>
                <input type="text" name="numero_microfonos" class="form-control">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar configuración</button>
            </div>
        </form>
    </div>
</div>

@endsection