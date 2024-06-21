<!-- Vista para el área 1 -->
@extends('layouts.reservacion_layouts')

@section('title', 'Horas reservadas - Laboratorio')

@section('title-area')
    {{-- Titulo --}}
    <h1 id="diaReservaLab" class="text-center fs-1"></h1>
    <p class="text-center fs-2">Laboratorio</p>
@endsection

{{-- Horarios reservados del laboratorio --}}
@section('content-reservaciones')
 <!-- Horas agendadas para el área de enfrente -->
<div class="d-flex">
   <!-- Horas agendadas para el área de adelante -->
  <div class="flex-grow-1">
    <h2>Adelante</h2>
    <div id="horasContainer" class="flex flex-fill" data-area="laboratorio"></div>
  </div>

   <!-- Horas agendadas para el área de atras -->
  <div class="flex-grow-1">
    <h2>Atrás</h2>
    <div id="horasContainerAtras" class="flex flex-fill" data-area="laboratorio-atras"></div>
  </div>
</div>

  {{-- <div class="modal fade" id="alertaModalReservacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alertaModalReservacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
      <div class="modal-content bg-danger">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white fs-1" id="alertaModalReservacionLabel">Reservación pronto a comenzar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-danger text-white fs-1">
          La clase empezará en 5 minutos
        </div>
      </div>
    </div>
  </div> --}}

  <!-- Modal con clase personalizada de tamaño -->
  <div class="modal modal-rojo fade modal-tamano-personalizado" id="alertaModalReservacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white fs-1" id="exampleModalLabel">Reservación próxima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-white fs-1">
                Una reservación está a punto de comenzar en 10 minutos.
            </div>
        </div>
    </div>
  </div>

@endsection

@section('scripts')
    <script src={{asset("js/HorasAgendadas.js")}}></script>
@endsection