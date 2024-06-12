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
        <div class="header">
            <table width="1170px" align="center" border="0">
            <tbody><tr>
                <td width="293px" style="background-color:#1B3257" align="center">
                    <img src="https://cuan.uaslp.mx/comunes/imagenes/logo_uaslp_blanco.png">
                </td>
                <td width="292px" style="background-color:#1B3257" align="center">
                    <img src="https://cuan.uaslp.mx/comunes/imagenes/logo_fe_blanco.png" style="margin">
                </td>
                <td width="585px" style="background-color:#1B3257">
                    <img src="https://cuan.uaslp.mx/comunes/imagenes/banner_der.png" width="585px" height="150">
                </td>
            </tr>
            <tr>
                <td bgcolor="#FFF" colspan="3" style="padding: 0px">
                    <table width="100%" style="margin-top:10px" align="center">
                    <tbody><tr>
                        <td style="height:32px; background-color:#1B3257" width="58.6%"><div style="color:#fff; margin-left:20px">Reservación de áreas comúnes</div></td>
                        <td style="background-color:#FCC200;padding: 0px;"><img src="https://cuan.uaslp.mx/comunes/imagenes/amarillo_azul.png"></td>
                        <td align="right" style="background-color:#FCC200; font-size:16px">"HUMANISMI ET SCIENTIA" &nbsp;</td>
                    </tr>
                    </tbody></table>
                </td>
            
            </tr></tbody></table>
        </div>

        @yield('title-area')
        {{-- Contenido específico --}}
        <div class="flex-fill">
            @yield('content-reservaciones')

        </div>

    </div>
    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
