@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Aula magna')

@section('table')
    <h1 class="fs-3 text-center mt-3 mb-0">Aula magna</h1>
    <div class="container mt-3 overflow-visible">
        <livewire:AulaMagnaTable theme="bootstrap-5" />
    </div>
@endsection



