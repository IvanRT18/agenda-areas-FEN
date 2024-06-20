@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Laboratorio')

@section('table')
    <div class="container mt-3 overflow-visible">
        <livewire:LaboratorioTable theme="bootstrap-5" />
    </div>
@endsection



