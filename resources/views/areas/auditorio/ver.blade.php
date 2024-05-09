<!-- Vista para el área 1 -->
@extends('layouts.reservacion_layouts')

@section('title', 'Horas reservadas - Auditorio')

@section('title-area')
    {{-- Titulo --}}
    <h1 id="diaReservaLab" class="text-center fs-1"></h1>
    <p class="text-center fs-2">Auditorio</p>
@endsection

@section('content-reservaciones')
 {{-- Horarios reservados del laboratorio --}}
 <div id="horasContainer" class="flex flex-col">
    <!-- Contenido específico de las horas agendadas para el área 1 -->
</div>
@endsection

@section('scripts')
    <script src={{asset("js/reservaciones_auditorio.js")}}></script>
@endsection