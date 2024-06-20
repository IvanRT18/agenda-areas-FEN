@extends('layouts.administrador_layout')

@section('title', 'Reservaciones Auditorio')

@section('table')
    <div class="container mt-3 overflow-visible">
        <livewire:AuditorioTable theme="bootstrap-5" />
    </div>
@endsection



