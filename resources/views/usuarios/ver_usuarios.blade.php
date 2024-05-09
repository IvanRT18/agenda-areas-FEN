@extends('layouts.administrador_layout')

@section('title', 'Usuarios registrados')

@section('table')
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th class="sortable-header" data-sort>Clave<span class="sort-icon"></span></th>
                <th>Apellido materno</th>
                <th>Apellido paterno</th>
                <th>Nombres</th>
                <th>Tipo</th>
                <th>Correo</th>
                <th>Editar / Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{$usuario->id}}</td>
                    <td>{{$usuario->clave}}</td>
                    <td>{{$usuario->apellido_materno}}</td>
                    <td>{{$usuario->apellido_paterno}}</td>
                    <td>{{$usuario->nombres}}</td>
                    <td>{{$usuario->tipo}}</td>
                    <td>{{$usuario->correo}}</td>
                    <td>
                        <a href="{{ url('administrador/usuarios/editar/'.$usuario->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <a href="{{ url('administrador/usuarios/eliminar/'.$usuario->id) }}" class="btn btn-danger btn-sm">Borrar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection



