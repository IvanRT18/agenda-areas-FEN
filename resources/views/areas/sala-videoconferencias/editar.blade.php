@extends('layouts.editar_reservacion_layout')

@section('title', 'Editar reservación - Sala videoconferencias')

@section('form-update')
<div class="card mt-3">
    <div class="card-header">
        <h4>Editar reservación - Sala videoconferencias
            <a href="/administrador/reservaciones/sala-videoconferencias" class="btn btn-danger float-end">Regresar</a>
        </h4>
    </div>
    <div class="card-body">

        <form action="{{ url('administrador/actualizar-reservacion/sala-videoconferencias/'. $reservacion->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="">ID Reservación</label>
                <input type="text" name="id" value="{{$reservacion->id}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">RPE</label>
                <input type="text" name="rpe" value="{{$reservacion->rpe}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Nombre Completo</label>
                <input type="text" name="nombre_completo" disabled value="{{App\Models\Usuario::obtenerNombreCompletoPorClave($reservacion->rpe)}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Fecha reservada</label>
                <input id="editFechaReservada" type="date" name="fecha_reservada" value="{{$reservacion->fecha_reservada}}" class="form-control">
            </div>
            <span id="pantallaCarga" class="hidden"> <p>Cargando horas reservadas...</p></span>
            <div class="form-group mb-3">
                <label for="">Hora de inicio de reservación</label>
                <input type="text" name="hora_reserva_inicio" id="hora_reserva_inicio" value="{{$reservacion->hora_reserva_inicio}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Hora de finalización de reservación</label>
                <input type="text" name="hora_reserva_final" id="hora_reserva_final" value="{{$reservacion->hora_reserva_final}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">Número de microfonos</label>
                <input type="text" name="numero_microfonos" value="{{$reservacion->numero_microfonos}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="usa_proyector">¿Usará el proyector?</label>
                <select class="form-select" aria-label="usa_proyector reservacion laboratorio" name="usa_proyector" id="usa_proyector">
                    <option value="0" {{ $reservacion->usa_proyector == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $reservacion->usa_proyector == '1' ? 'selected' : '' }}>Sí</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="usa_computadora">¿Usará la computadora del aula magna?</label>
                <select class="form-select" aria-label="usa_computadora reservacion laboratorio" name="usa_computadora" id="usa_computadora">
                    <option value="0" {{ $reservacion->usa_computadora == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $reservacion->usa_computadora == '1' ? 'selected' : '' }}>Sí</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="usa_bocinas">¿Usará las bocinas?</label>
                <select class="form-select" aria-label="usa_bocinas reservacion laboratorio" name="usa_bocinas" id="usa_bocinas">
                    <option value="0" {{ $reservacion->usa_bocinas == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $reservacion->usa_bocinas == '1' ? 'selected' : '' }}>Sí</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar reservación</button>
            </div>
        </form>
    </div>
</div>

@endsection