@extends('layouts.editar_reservacion_layout')

@section('title', 'Editar reservación - Laboratorio')

@section('form-update')

<div class="card">
    <div class="card-header">
        <h4>Editar cita - Laboratorio
            <a href="{{ url('administrador') }}" class="btn btn-danger float-end">Regresar</a>
        </h4>
    </div>
    <div class="card-body">

        <form action="{{ url('administrador/actualizar-reservacion/laboratorio/'. $reservacion->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="">ID Cita</label>
                <input type="text" name="id" value="{{$reservacion->id}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">RPE</label>
                <input type="text" name="rpe" value="{{$reservacion->rpe}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Nombre Completo</label>
                <input type="text" name="nombre_completo" value="{{$reservacion->usuario->getNombreCompleto()}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Fecha reservada</label>
                <input id="editFechaReservada" type="date" name="fecha_reservada" value="{{$reservacion->fecha_reservada}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Hora de inicio de reservación</label>
                <input type="text" name="hora_reserva_inicio" id="hora_reserva_inicio" value="{{$reservacion->hora_reserva_inicio}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Hora de finalización de reservación</label>
                <input type="text" name="hora_reserva_final" id="hora_reserva_final" value="{{$reservacion->hora_reserva_final}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Número de equipos</label>
                <input type="text" name="numero_equipos" value="{{$reservacion->numero_equipos}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">¿Usa el proyector del laboratorio?</label>
                <input type="text" name="usa_proyector" value="{{$reservacion->usa_proyector}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Requerimientos adicionales</label>
                <textarea type="text" name="req_adicionales" class="form-control">{{$reservacion->req_adicionales}}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="">Materia</label>
                <input type="text" name="materia" value="{{$reservacion->materia}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Actividad</label>
                <input type="text" name="actividad" value="{{$reservacion->actividad}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Nombre del Curso</label>
                <input type="text" name="nombre_curso" value="{{$reservacion->nombre_curso}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar reservación</button>
            </div>
        </form>
    </div>
</div>

@endsection