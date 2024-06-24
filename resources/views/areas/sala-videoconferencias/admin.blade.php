@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Sala videoconferencias')

@section('table')
    <h1 class="fs-3 text-center mt-3 mb-0">Sala videoconferencias</h1>
    <div class="container mt-3 overflow-visible">
        <livewire:SalaVideoTable theme="bootstrap-5" />
    </div>
@endsection



