@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Sala videoconferencias')

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
                <th>Usa Proyector</th>
                <th>Número de microfonos</th>
                <th>Usa bocina</th>
                <th>Usa computadora</th>
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
                    <td>{{$reservacion->usa_proyector}}</td>
                    <td>{{$reservacion->numero_microfonos}}</td>
                    <td>{{$reservacion->usa_computadora}}</td>
                    <td>{{$reservacion->usa_bocinas}}</td>
                    <td>
                        <a href="{{ url('administrador/editar-reservacion/sala-videoconferencias/'.$reservacion->id) }}" class="btn btn-primary btn-sm">Edita</a>
                        <a href="{{ url('administrador/borrar-reservacion/sala-videoconferencias/'.$reservacion->id) }}" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection



