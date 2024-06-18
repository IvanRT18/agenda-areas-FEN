@extends('layouts.editar_reservacion_layout')

@section('title', 'Editar reservación - Laboratorio')

@section('form-update')

<div class="card">
    <div class="card-header">
        <h4>Editar reservación - Laboratorio
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
                <input type="text" name="rpe" id="rpe" value="{{$reservacion->rpe}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Nombre Completo</label>
                <input type="text" name="nombre_completo" id="nombre_completo" disabled value="{{App\Models\Usuario::obtenerNombreCompletoPorClave($reservacion->rpe)}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Fecha reservada</label>
                <input id="editFechaReservada" type="date" name="fecha_reservada" id="fecha_reservada" value="{{$reservacion->fecha_reservada}}" class="form-control">
                <span id="pantallaCarga" class="hidden"> <p>Cargando horas reservadas...</p></span>
            </div>
            <div class="form-group mb-3">
                <label for="ubicacion">Ubicación</label>
                <select class="form-select" aria-label="ubicacion reservacion laboratorio" name="ubicacion" id="ubicacion">
                    <option value="Adelante" {{ $reservacion->ubicacion == 'Adelante' ? 'selected' : '' }} >Adelante</option>
                    <option value="Atrás" {{ $reservacion->ubicacion == 'Atrás' ? 'selected' : '' }}>Atrás</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="numero_equipos">Número de equipos</label>
                <input type="text" name="numero_equipos" id="numero_equipos" value="{{$reservacion->numero_equipos}}" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="">Hora de inicio de reservación</label>
                <input type="text" name="hora_reserva_inicio" id="hora_reserva_inicio" value="{{$reservacion->hora_reserva_inicio}}" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="">Hora de finalización de reservación</label>
                <input type="text" name="hora_reserva_final" id="hora_reserva_final" value="{{$reservacion->hora_reserva_final}}" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="">¿Usa el proyector del laboratorio?</label>
                <input type="text" name="usa_proyector" id="usa_proyector" value="{{$reservacion->usa_proyector}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Requerimientos adicionales</label>
                <textarea type="text" name="req_adicionales" id="req_adicionales" class="form-control">{{$reservacion->req_adicionales}}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="">Materia</label>
                <input type="text" name="materia" id="materia" value="{{$reservacion->materia}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Actividad</label>
                <input type="text" name="actividad" id="actividad" value="{{$reservacion->actividad}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Nombre del Curso</label>
                <input type="text" name="nombre_curso" id="nombre_curso" value="{{$reservacion->nombre_curso}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar reservación</button>
            </div>
        </form>
    </div>
</div>

@endsection