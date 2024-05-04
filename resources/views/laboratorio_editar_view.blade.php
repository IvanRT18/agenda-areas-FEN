<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reservas laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">

    {{-- @vite('resources/css/app.css') --}}

    <!-- Bootstrap -->
    {{-- <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/bootstrap.min.css' rel="stylesheet" />  --}}
    
    {{-- Nuevo bootstrap --}}
    <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">
    <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>
    
    <!-- UASLP -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
    <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}> 

     {{-- Timepicker css --}}
     <link rel="stylesheet" href="{{ asset('js/timepicker/jquery.timepicker.css') }}">
     
     {{-- flatpickr css --}}
     <link rel="stylesheet" href="{{asset('js/flatpickr/flatpickr.min.css')}}">
</head>
<body>
    <div class="container">

        {{-- Header --}}
        <div class="Encabezado">
            <div class="escudos">
                <div class="escudo escudoUASLP">
                    <a href={{url('http://www.uaslp.mx')}}>
                        <img id="UASLPLogo" src='https://serviciosenlinea.uaslp.mx/LoginGateway/Imagenes/UASLP.png' class="img-responsive">
                        <img id="UASLPLogoMobile" src='https://serviciosenlinea.uaslp.mx/LoginGateway/Imagenes/UASLPVertical.png' class="img-responsive">
                    </a>
                </div>
                <div class="lineaEscudo"></div>
                <div class="titulo">
                    <a href="/">
                        <span id="Titulo">Agenda de áreas - Fac. Enfermería y Nutrición</span>
                        <span id="TituloMobile">Agenda de áreas</span>
                    </a>
                </div>
            </div>
            <div class="foto"></div>
        </div>

        <div class="Idioma" style="display:block"></div>

        <div class="Lema">
            <div class="LemaAzul"><img src='https://serviciosenlinea.uaslp.mx/LoginGateway/Imagenes/amarillo_azul.png'></div>
            <div class="LemaAmarillo">"Siempre autónoma. Por mi patria educaré."</div>
        </div>

    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
    
                {{-- @if (session('status'))
                    <h6 class="alert alert-success">{{ session('status') }}</h6>
                @endif --}}

                @if (session('status'))
                    <div class="flex flex-row justify-arnd alert alert-success">
                        <h6 class="">{{ session('status') }}</h6>
                        <button id="close-btn">X</button>
                    </div>
                @endif
    
                <div class="card">
                    <div class="card-header">
                        <h4>Editar cita - Laboratorio
                            <a href="{{ url('administrador') }}" class="btn btn-danger float-end">Regresar</a>
                        </h4>
                    </div>
                    <div class="card-body">
    
                        <form action="{{ url('actualizar-reservacion/laboratorio/'. $reservacion->id) }}" method="POST">
                            @csrf
                            @method('PUT')
    
                            <div class="form-group mb-3">
                                <label for="">ID Cita</label>
                                <input type="text" name="id" value="{{$reservacion->id}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">RPE</label>
                                <input type="text" name="rpe" value="{{$reservacion->rpe}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Nombre Completo</label>
                                <input type="text" name="nombre_completo" value="{{$reservacion->nombre_completo}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Fecha reservada</label>
                                <input id="editFechaReservada" type="date" name="fecha_reservada" value="{{$reservacion->fecha_reservada}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Hora de inicio de reservación</label>
                                <input type="text" name="hora_reserva_inicio" id="hora_reserva_inicio" value="{{$reservacion->hora_reserva_inicio}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Hora de finalización de reservación</label>
                                <input type="text" name="hora_reserva_final" id="hora_reserva_final" value="{{$reservacion->hora_reserva_final}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Número de equipos</label>
                                <input type="text" name="numero_equipos" value="{{$reservacion->numero_equipos}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">¿Usa el proyector del laboratorio?</label>
                                <input type="text" name="usa_proyector" value="{{$reservacion->usa_proyector}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Requerimientos adicionales</label>
                                <textarea type="text" name="req_adicionales" class="form-control">{{$reservacion->req_adicionales}}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Materia</label>
                                <input type="text" name="materia" value="{{$reservacion->materia}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Actividad</label>
                                <input type="text" name="actividad" value="{{$reservacion->actividad}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Nombre del Curso</label>
                                <input type="text" name="nombre_curso" value="{{$reservacion->nombre_curso}}" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Actualizar reservación</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{asset('js/editCita.js')}}"></script>
</html>