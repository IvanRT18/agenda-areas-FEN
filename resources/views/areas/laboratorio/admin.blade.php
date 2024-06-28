@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Laboratorio')

@section('table')
    <h1 class="fs-3 text-center mt-3 mb-0">Laboratorio</h1>
    <div class="container mt-3 overflow-visible">
        <livewire:LaboratorioTable theme="bootstrap-5" />
    </div>
@endsection