<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Registrar nuevo usuario | Agenda Áreas FEN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />   
        <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">
    
        {{-- FEN estilos --}}
        <link rel="stylesheet" type="text/css" href="https://cuan.uaslp.mx/adds/estilos/pagina.css">

        <!-- Bootstrap --> 
        {{-- <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/bootstrap.min.css' rel="stylesheet" />  --}}
        <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">
        <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>

        <!-- UASLP -->
        <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
        
        {{-- Flatpickr css --}}
        <link rel="stylesheet" href="js/flatpickr/flatpickr.min.css">
    
        {{-- Timepicker css --}}
        <link rel="stylesheet" href="{{ asset('js/timepicker/jquery.timepicker.css') }}">

        {{-- Agenda Áreas css --}}
        <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}>
    </head>
<body>
    <div class="container">
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
                <td bgcolor="#FFF" colspan="3">
                    <table width="100%" style="margin-top:10px" align="center">
                    <tbody><tr>
                        <td style="height:32px; background-color:#1B3257" width="58.6%"><div style="color:#fff; margin-left:20px">Reservación de áreas comúnes</div></td>
                        <td style="background-color:#FCC200"><img src="https://cuan.uaslp.mx/comunes/imagenes/amarillo_azul.png"></td>
                        <td align="right" style="background-color:#FCC200; font-size:16px">"HUMANISMI ET SCIENTIA" &nbsp;</td>
                    </tr>
                    </tbody></table>
                </td>
            
            </tr></tbody></table>
        </div>

        {{-- Alerta de confimación --}}
        @if (session('status'))
            <div class="flex flex-row justify-arnd alert alert-success">
                <h6 class="">{{ session('status') }}</h6>
                <button id="close-btn">X</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="card mt-3">
            <div class="card-header">
                <h4>Registrar usuario
                    <a href="{{ url('administrador/usuarios') }}" class="btn btn-danger float-end">Regresar</a>
                </h4>
            </div>
            <div class="card-body">

                <form action="{{ url('administrador/usuarios/registrar') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="">RPE</label>
                        <input type="text" name="clave" class="form-control" value="{{ old('clave') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Apellido paterno</label>
                        <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" value="{{ old('apellido_paterno') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Apellido materno</label>
                        <input type="text" name="apellido_materno" class="form-control" value="{{ old('apellido_materno') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Nombre(s)</label>
                        <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tipo">Tipo de usuario</label>
                        <select class="form-select" aria-label="Default select example" name="tipo">
                            <option value="docente" {{ old('tipo') == 'docente' ? 'selected' : '' }} >Docente</option>
                            <option value="administrador" {{ old('tipo') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Correo</label>
                        <input type="email" name="correo" class="form-control" value="{{ old('correo') }}">
                    </div>
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">Registrar usuario</button>
                    </div>
                </form>

            </div>
        </div>
        <div id="Creditos" style="margin-top: 200px">          
            <h4>Universidad Autónoma de San Luis Potosí</h4>
                Álvaro Obregón #64, Col. Centro, C.P. 78000<br>
            San Luis Potosí, S.L.P. México                <br>
            Tel.  (444) 826 2300<br>
            ©<span id="lbAño">2024</span> © Todos los derechos reservados.           
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{asset('js/registrarUsuario.js')}}"></script>
</html>