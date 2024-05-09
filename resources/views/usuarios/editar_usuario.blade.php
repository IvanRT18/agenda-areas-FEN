@extends('layouts.editar_reservacion_layout')

@section('title', 'Editar reservación - Laboratorio')

@section('form-update')

<div class="card mt-3">
    <div class="card-header">
        <h4>Actualizar usuario
            <a href="{{ url('administrador/usuarios') }}" class="btn btn-danger float-end">Regresar</a>
        </h4>
    </div>
    <div class="card-body">

        <form action="{{ url('administrador/usuarios/actualizar/'. $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="">RPE</label>
                <input type="text" name="clave" class="form-control" value="{{$usuario->clave}}">
            </div>
            <div class="form-group mb-3">
                <label for="">Apellido paterno</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" value="{{$usuario->apellido_paterno}}">
            </div>
            <div class="form-group mb-3">
                <label for="">Apellido materno</label>
                <input type="text" name="apellido_materno" class="form-control" value="{{ $usuario->apellido_materno }}">
            </div>
            <div class="form-group mb-3">
                <label for="">Nombre(s)</label>
                <input type="text" name="nombres" id="nombres" class="form-control" value="{{ $usuario->nombres }}">
            </div>
            <div class="form-group mb-3">
                <select class="form-select" aria-label="Default select example" name="tipo">
                    <option value="docente" {{ $usuario->tipo == 'docente' ? 'selected' : '' }} >Docente</option>
                    <option value="administrador" {{ $usuario->tipo == 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="">Correo</label>
                <input type="email" name="correo" class="form-control" value="{{$usuario->correo }}">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
            </div>
        </form>

    </div>
</div>

@endsection