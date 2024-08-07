<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reservación de áreas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://cuan.uaslp.mx/uaslp_icon.ico">

    {{-- @vite('resources/css/app.css') --}}

    <link rel="stylesheet" type="text/css" href="https://cuan.uaslp.mx/adds/estilos/pagina.css">

    <!-- Bootstrap -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/bootstrap.min.css' rel="stylesheet" /> 
    <!-- UASLP -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
    
    {{-- flatpickr css --}}
    <link rel="stylesheet" href="js/flatpickr/flatpickr.min.css">

    {{-- Timepicker css --}}
    <link rel="stylesheet" href="{{ asset('js/timepicker/jquery.timepicker.css') }}">

    {{-- Agenda css --}}
    <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}>
    
    {{-- flatpickr css --}}
    <link rel="stylesheet" href="js/flatpickr/flatpickr.min.css">

</head>

<body>
    <div class="container">
        <div class="header">
            <table width="1170px" align="center" border="0">
            <tbody><tr>
                <td width="293px" style="background-color:#1B3257" align="center">
                    <img src="{{url('/img/logo_uaslp_blanco.png')}}">
                </td>
                <td width="292px" style="background-color:#1B3257" align="center">
                    <img src="{{url('/img/logo_fe_blanco.png')}}" style="margin">
                </td>
                <td width="585px" style="background-color:#1B3257">
                    <img src="{{url('/img/banner_der.png')}}" width="585px" height="150">
                </td>
            </tr>
            <tr>
                <td bgcolor="#FFF" colspan="3">
                    <table width="100%" style="margin-top:10px" align="center">
                    <tbody><tr>
                        <td style="height:32px; background-color:#1B3257" width="58.6%"><div style="color:#fff; margin-left:20px">Reservación de áreas comúnes</div></td>
                        <td style="background-color:#FCC200"><img src="{{url('/img/amarillo_azul.png')}}"></td>
                        <td align="right" style="background-color:#FCC200; font-size:16px">"HUMANISMI ET SCIENTIA" &nbsp;</td>
                    </tr>
                    </tbody></table>
                </td>
            
            </tr></tbody></table>
        </div>

        {{-- Alerta de exito --}}
        @if (session('status'))
            <div class="flex flex-row justify-arnd alert alert-success ">
                <h6 class="">{{ session('status') }}</h6>
                <button id="close-btn">X</button>
            </div>
        @endif

        {{-- Alerta de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div style="
        display: flex;
        justify-content: space-between;
        margin-top: 3rem;
    ">
    
            {{-- Muestra el nombre del usuario --}}
            <h1>
                @if ($usuario && $usuario->getNombreCompleto())
                    {{$usuario->getNombreCompleto()}}                
                @else
                    {{session('user')}}                    
                @endif
            </h1>

            {{-- Si es un usuario administrador --}}
            @if($usuario && $usuario->esAdministrador())
                <form action="{{url('/administrador')}}">
                    <input type="submit" value="Administrador" class="btn btn-primary">
                </form>
            @endif
    
            <form action="{{url('/logout')}}">
                <input type="submit" value="Cerrar Sesión" class="btn btn-primary">
            </form>
        </div>
        
       @livewire('area-selector')
    </div>


    <div id="Creditos" style="margin-top: 500px">          
        <h4>Universidad Autónoma de San Luis Potosí</h4>
            Álvaro Obregón #64, Col. Centro, C.P. 78000<br>
        San Luis Potosí, S.L.P. México                <br>
        Tel.  (444) 826 2300<br>
        ©<span id="lbAño"></span> © Todos los derechos reservados.           
   </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
<script src="js/index.js"></script>
</html>