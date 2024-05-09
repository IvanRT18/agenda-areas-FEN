@extends('layouts.editar_reservacion_layout')

@section('title', 'Editar reservación - Auditorio')

@section('form-update')
<div class="card">
    <div class="card-header">
        <h4>Editar reservacion auditorio
            <a href="/administrador/reservaciones/auditorio" class="btn btn-danger float-end">Regresar</a>
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ url('administrador/actualizar-reservacion/auditorio/'. $reservacion->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="">ID Reservación</label>
                <input type="text" name="id" value="{{$reservacion->id}}" class="form-control">
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
                <label for="">Número de microfonos</label>
                <input type="text" name="numero_microfonos" value="{{$reservacion->numero_microfonos}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">¿Usará el proyector?</label>
                <input type="text" name="usa_proyector" value="{{$reservacion->usa_proyector}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">¿Usará la computadora del auditorio?</label>
                <input type="text" name="usa_computadora" value="{{$reservacion->usa_computadora}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="">¿Usará las bocinas?</label>
                <input type="text" name="usa_bocinas" value="{{$reservacion->usa_bocinas}}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar reservación</button>
            </div>
        </form>
    </div>
</div>

@endsection



