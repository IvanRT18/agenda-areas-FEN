<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reservas laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">

    {{-- @vite('resources/css/app.css') --}}
    @vite('resources/js/app.js')
    <!-- Bootstrap -->
    {{-- <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/bootstrap.min.css' rel="stylesheet" />  --}}
    
    {{-- Nuevo bootstrap --}}
    <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">
    <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>
    
    <!-- UASLP -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
    <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}> 
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


        @if (session('status'))
            <h6 class="alert alert-success">{{ session('status') }}</h6>
        @endif

        <div class="container-fluid d-inline-flex justify-content-end mt-3">
            <a href="{{ url('dias-inhabilitados-laboratorio') }}" class="btn btn-info btn-sm fs-6">Días inhabilitados</a>
        </div>

        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de equipos</th>
                    <th>Fecha Reservada</th>
                    <th>Empieza</th>
                    <th>Termina</th>
                    <th>Usa Proyector</th>
                    <th>Requerimientos adicionales</th>
                    <th>RPE</th>
                    <th>Nombre Completo</th>
                    <th>Materia</th>
                    <th>Actividad</th>
                    <th>Nombre Curso</th>
                    <th>Editar / Eliminar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservaciones as $reservacion)
                    <tr>
                        <td>{{$reservacion->id}}</td>
                        <td>{{$reservacion->numEquipos}}</td>
                        <td>{{$reservacion->fechaReservada}}</td>
                        <td>{{$reservacion->horaReservaInicio}}</td>
                        <td>{{$reservacion->horaReservaFinal}}</td>
                        <td>{{$reservacion->usaProyector}}</td>
                        <td>{{$reservacion->reqAdicionales}}</td>
                        <td>{{$reservacion->Rpe}}</td>
                        <td>{{$reservacion->nombreCompleto}}</td>
                        <td>{{$reservacion->materia}}</td>
                        <td>{{$reservacion->actividad}}</td>
                        <td>{{$reservacion->nombreCurso}}</td>
                        <td>
                            <a href="{{ url('/edita-cita-laboratorio/'.$reservacion->id) }}" class="btn btn-primary btn-sm">Edita</a>
                            <a href="{{ url('borra-cita-laboratorio/'.$reservacion->id) }}" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        

        <!-- Modal -->
        <div class="modal fade modal-dialog modal-dialog-scrollable" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                ...
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
        </div>

    </div>

    
</body>
</html>