<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Horas reservadas laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">

    {{-- @vite('resources/css/app.css') --}}


    <!-- old Bootstrap -->
    {{-- <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/bootstrap.min.css' rel="stylesheet" />  --}}

    {{-- Nuevo bootstrap --}}
    <link rel="stylesheet" href="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">

    <!-- UASLP -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />

    {{-- Estilos propios --}}
    {{-- <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}>  --}}
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
                        <span id="Titulo">Horarios reservados</span>
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

        {{-- Titulo --}}
        <div class="container-fluid ">
            <h1 id="diaReservaLab" class="text-center fs-1"></h1>
            <p class="text-center fs-2">Laboratorio de cómputo</p>
        </div>


        {{-- Horarios reservados del laboratorio --}}
        <div id="horasContainer" class="flex flex-col">
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/reservasLab.js"></script>
</html>