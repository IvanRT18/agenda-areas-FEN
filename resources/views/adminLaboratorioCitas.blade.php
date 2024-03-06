<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reservas laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">

    {{-- @vite('resources/css/app.css') --}}


    <!-- Bootstrap -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/bootstrap.min.css' rel="stylesheet" /> 
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


        <table class="table table-bordered table-striped mt-table">
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
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Horarios reservados del laboratorio --}}
        
    </div>

    
</body>
</html>