@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Aula magna')

@section('table')
    <div class="container mt-3 overflow-visible">
        <livewire:AulaMagnaTable theme="bootstrap-5" />
    </div>
@endsection



