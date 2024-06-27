@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Auditorio')

@section('table')
    <h1 class="fs-3 text-center mt-3 mb-0">Auditorio</h1>
    <div class="container mt-3 overflow-visible">
        <livewire:AuditorioTable theme="bootstrap-5" />
    </div>
@endsection