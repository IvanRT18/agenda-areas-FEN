@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Sala videoconferencias')

@section('table')
    <div class="container mt-3 overflow-visible">
        <livewire:SalaVideoTable theme="bootstrap-5" />
    </div>
@endsection



