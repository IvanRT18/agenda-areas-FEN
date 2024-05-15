<!-- layout.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">
    {{-- Nuevo bootstrap --}}
    {{-- <link rel="stylesheet" href="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"> --}}
    <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">
    <!-- UASLP -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
    {{-- Estilos --}}
    @yield('styles')

    <style>
        /* Estilo para modal con tamaño personalizado */
.modal-tamano-personalizado .modal-dialog {
    max-width: 50vw; /* Ancho máximo del modal: 50% del ancho de la pantalla */
    height: 50vh; /* Altura del modal: 50% de la altura de la pantalla */
}

/* Centrar el modal verticalmente */
.modal-tamano-personalizado .modal-content {
    height: 100%; /* Establecer altura al 100% del modal para centrarlo */
    display: flex;
    flex-direction: column;
    justify-content: center;
}

    /* Estilo para modal con fondo rojo */
    .modal-rojo .modal-content {
        background-color: #FFAF45; /* Rojo */
        color: white; /* Texto blanco para mejor contraste */
    }
    </style>

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
                        <span id="Titulo">Horarios de áreas reservadas - FEN</span>
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

        {{-- Contenido específico --}}
        <div class="container-fluid">
            @yield('title-area')
        </div>

         @yield('content-reservaciones')
    </div>
    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
