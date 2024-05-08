@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Laboratorio')

@section('table')
<table class="table table-bordered table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>RPE</th>
            <th>Nombre Completo</th>
            <th class="sortable-header" data-sort>Fecha Reservada<span class="sort-icon"></span></th>
            <th>Empieza</th>
            <th>Termina</th>
            <th>Número de equipos</th>
            <th>Usa Proyector</th>
            <th>Requerimientos adicionales</th>
            <th>Materia</th>
            <th>Actividad</th>
            <th>Nombre Curso</th>
            <th>Editar / Eliminar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservaciones as $reservacion)
            <tr>
                <td>{{$reservacion->id}}</td>
                <td>{{$reservacion->rpe}}</td>
                <td>{{$reservacion->nombre_completo}}</td>
                <td>{{$reservacion->fecha_reservada}}</td>
                <td>{{$reservacion->hora_reserva_inicio}}</td>
                <td>{{$reservacion->hora_reserva_final}}</td>
                <td>{{$reservacion->numero_equipos}}</td>
                <td>{{$reservacion->usa_proyector}}</td>
                <td>{{$reservacion->req_adicionales}}</td>
                <td>{{$reservacion->materia}}</td>
                <td>{{$reservacion->actividad}}</td>
                <td>{{$reservacion->nombre_curso}}</td>
                <td>
                    <a href="{{ url('administrador/editar-reservacion/laboratorio/'.$reservacion->id) }}" class="btn btn-primary btn-sm">Edita</a>
                    <a href="{{ url('borra-cita-laboratorio/'.$reservacion->id) }}" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection



