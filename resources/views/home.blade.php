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

        @if (session('status'))
            <div class="flex flex-row justify-arnd alert alert-success ">
                <h6 class="">{{ session('status') }}</h6>
                <button id="close-btn">X</button>
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
        

        {{-- Radios de areas para reservar --}}
       <div class="flex flex-col contenido gap align-center">
        <h1>Área para reservar:</h1>
        <div class="radio-container col-md-6" id="radioContainer">
            <label for="radioAuditorio"><input type="radio" name="radioItem" id="radioAuditorio">Auditorio</label>
            <label for="radioAulaMagna"><input type="radio" name="radioItem" id="radioAulaMagna">Aula magna (propedéutico)</label>
            <label for="radioLab"><input type="radio" name="radioItem" id="radioLab">Laboratorio de cómputo</label>
            <label for="radioSalaV"><input type="radio" name="radioItem" id="radioSalaV">Sala de videoconferencia</label>
       </div>


       {{-- Formulario Laboratorio de cómputo--}}
       <form name="formularioLabComp" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('registra-reservacion/laboratorio')}}" id="formularioLabComp">
           @csrf
        <div class="flex flex-col col-xs-12 gap">
            <div class="flex flex-col">
                <label for="fecha_reservada">Fecha para reservar:</label>
                <input type="date" id="fecha_reservada" name="fecha_reservada" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+14 days')) }}" required>
                <span id="pantallaCarga" class="hidden"> <p>Cargando horas reservadas...</p></span>
            </div>

            <div class="flex flex-col">
                <label for="hora_reserva_inicio">Hora para reservar:</label>
                <div class="col-12">
                    {{-- <input type="time" id="hora_reserva_inicio" name="hora_reserva_inicio" min="07:00" max="20:00" class="w-100" required> --}}
                    <input type="text" id="hora_reserva_inicio" name="hora_reserva_inicio" class="w-100" required/>
                    {{-- <span class="validity"></span> --}}
                </div>
            </div>

            <div class="flex flex-col">
                <label for="hora_reserva_final">A</label>
                <div class="col-12">
                    {{-- <input type="time" id="hora_reserva_final" name="hora_reserva_final" min="07:00" max="20:00" class="w-100" required> --}}
                    <input type="text" id="hora_reserva_final" name="hora_reserva_final" class="w-100" required/>
                    {{-- <span class="validity"></span> --}}
                </div>
            </div>

            <div>
                <label for="numero_equipos">¿Cuantos equipos de cómputo usará?</label>
                <div class="flex flex-col">
                    <input id="numero_equipos" type="number" name="numero_equipos" min="1" max="40" required/>
                </div>
            </div>
            
            <div>
                <label for="usa_proyector"><input type="checkbox" name="usa_proyector" id="usa_proyector" value="true">¿Usará el proyector del laboratorio? (Opcional)</label>
            </div>

            <div class="flex flex-col">
                <strong>Opcional:</strong>
            <label for="req_adicionales">¿Necesita de algo en particular? (Software, cañones, bocinas, étc)</label>
            <textarea name="req_adicionales" id="req_adicionales" rows="5" style="resize: none"></textarea>
            </div>
            
            {{-- <div class="flex flex-col">
                <label for="rpe">RPE</label>
                <input type="text" name="rpe" id="rpe" required>
            </div>

            <div class="flex flex-col">
                <label for="nombre_completo">Nombre completo</label>
                <input type="text" name="nombre_completo" id="nombre_completo" required>
            </div> --}}

            <div class="flex flex-col">
                <label for="materia">Materia</label>
                <input type="text" name="materia" id="materia" required>
            </div>

            <div class="flex flex-col">
                <label for="actividad">Actividad a realizar (clase/curso/examen/otro)</label>
                <input type="text" name="actividad" id="actividad" required>
            </div>

            <div class="flex flex-col">
                <label for="nombre_curso">Nombre del curso a realizar</label>
                <input type="text" name="nombre_curso" id="nombre_curso">
            </div>

            <input type="submit" name="AgendarButton" value="Agendar" id="AgendarButton" class="btn btn-primary">
        </div>
        </form>

    {{-- Formulario auditorio --}}
    <form name="formAuditorio" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('registra-reservacion/auditorio')}}" id="formAuditorio">
        @csrf
        <div class="flex flex-col col-xs-12 gap">
            <div class="flex flex-col">
                <label for="fecha_reservada_auditorio">Fecha para reservar:</label>
                <input type="date" id="fecha_reservada_auditorio" name="fecha_reservada_auditorio" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+14 days')) }}" required>
                <span id="pantallaCargaAuditorio" class="hidden"> <p>Cargando horas reservadas...</p></span>
            </div>
    
            <div class="flex flex-col">
                <label for="hora_reserva_inicio_auditorio">Hora para reservar:</label>
                <div class="col-12">
                    <input type="text" id="hora_reserva_inicio_auditorio" name="hora_reserva_inicio_auditorio" class="w-100" required/>
                </div>
            </div>
    
            <div class="flex flex-col">
                <label for="hora_reserva_final_auditorio">A</label>
                <div class="col-12">
                    <input type="text" id="hora_reserva_final_auditorio" name="hora_reserva_final_auditorio" class="w-100" required/>
                </div>
            </div>
    
            <div>
                <input type="checkbox" name="usa_proyector_auditorio" id="usa_proyector_auditorio" value="true">
                <label for="usa_proyector_auditorio">¿Usará el proyector?</label>
            </div>
    
            <div class="flex flex-col">
                <label for="numero_microfonos_auditorio">
                    ¿Cuantos microfonos usará?
                </label>
                <input type="number" name="numero_microfonos_auditorio" id="numero_microfonos" min="0" max="2">
            </div>
    
            <div>
                <input type="checkbox" name="usa_computadora_auditorio" id="usa_computadora_auditorio" value="true">
                <label for="usa_computadora_auditorio">¿Usará la computadora?</label>
            </div>
    
            <div>
                <input type="checkbox" name="usa_bocinas_auditorio" id="usa_bocinas_auditorio" value="true">
                <label for="usa_bocinas_auditorio">
                    ¿Usará las bocinas? (multimedia, videos, étc)
                </label>
            </div>
        </div>
        <input type="submit" name="AgendarButtonAuditorio" value="Agendar" id="AgendarButtonAuditorio" class="btn btn-primary">
    </form>

    {{-- Formulario aula magna --}}
    <form name="formAulaMagna" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('registra-reservacion/aula-magna')}}" id="formAulaMagna">
        @csrf
        <div class="flex flex-col col-xs-12 gap">
            <div class="flex flex-col">
                <label for="fecha_reservada_magna">Fecha para reservar:</label>
                <input type="date" id="fecha_reservada_magna" name="fecha_reservada_magna" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+14 days')) }}" required>
                <span id="pantallaCargaMagna" class="hidden"> <p>Cargando horas reservadas...</p></span>
            </div>
    
            <div class="flex flex-col">
                <label for="hora_reserva_inicio_magna">Hora para reservar:</label>
                <div class="col-12">
                    <input type="text" id="hora_reserva_inicio_magna" name="hora_reserva_inicio_magna" class="w-100" required/>
                </div>
            </div>
    
            <div class="flex flex-col">
                <label for="hora_reserva_final_magna">A</label>
                <div class="col-12">
                    <input type="text" id="hora_reserva_final_magna" name="hora_reserva_final_magna" class="w-100" required/>
                </div>
            </div>
    
            <div>
                <input type="checkbox" name="usa_proyector_magna" id="usa_proyector_magna" value="true">
                <label for="usa_proyector_magna">¿Usará el proyector?</label>
            </div>
    
            <div class="flex flex-col">
                <label for="numero_microfonos_magna">
                    ¿Cuantos microfonos usará?
                </label>
                <input type="number" name="numero_microfonos_magna" id="numero_microfonos_magna" min="0" max="2">
            </div>
    
            <div>
                <input type="checkbox" name="usa_computadora_magna" id="usa_computadora_magna" value="true">
                <label for="usa_computadora_magna">¿Usará la computadora?</label>
            </div>
    
            <div>
                <input type="checkbox" name="usa_bocinas_magna" id="usa_bocinas_magna" value="true">
                <label for="usa_bocinas_magna">
                    ¿Usará las bocinas? (multimedia, videos, étc)
                </label>
            </div>
        </div>
        <input type="submit" name="AgendarButtonMagna" value="Agendar" id="AgendarButtonMagna" class="btn btn-primary">
    </form>

    {{-- Formulario sala de videoconferencia --}}
    <form name="formSalaV" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('registra-reservacion/sala-videoconferencias')}}" id="formSalaV">
        @csrf
        <div class="flex flex-col col-xs-12 gap">
            <div class="flex flex-col">
                <label for="fecha_reservada_sala_video">Fecha para reservar:</label>
                <input type="date" id="fecha_reservada_sala_video" name="fecha_reservada_sala_video" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+14 days')) }}" required>
                <span id="pantallaCargaSalaVideo" class="hidden"> <p>Cargando horas reservadas...</p></span>
            </div>
    
            <div class="flex flex-col">
                <label for="hora_reserva_inicio_sala_video">Hora para reservar:</label>
                <div class="col-12">
                    <input type="text" id="hora_reserva_inicio_sala_video" name="hora_reserva_inicio_sala_video" class="w-100" required/>
                </div>
            </div>
    
            <div class="flex flex-col">
                <label for="hora_reserva_final_sala_video">A</label>
                <div class="col-12">
                    <input type="text" id="hora_reserva_final_sala_video" name="hora_reserva_final_sala_video" class="w-100" required/>
                </div>
            </div>
    
            <div>
                <input type="checkbox" name="usa_proyector_sala_video" id="usa_proyector_sala_video" value="true">
                <label for="usa_proyector_sala_video">¿Usará el proyector?</label>
            </div>
    
            <div class="flex flex-col">
                <label for="numero_microfonos_sala_video">
                    ¿Cuantos microfonos usará?
                </label>
                <input type="number" name="numero_microfonos_sala_video" id="numero_microfonos_sala_video" min="0" max="2">
            </div>
    
            <div>
                <input type="checkbox" name="usa_computadora_sala_video" id="usa_computadora_sala_video" value="true">
                <label for="usa_computadora_sala_video">¿Usará la computadora?</label>
            </div>
    
            <div>
                <input type="checkbox" name="usa_bocinas_sala_video" id="usa_bocinas_sala_video" value="true">
                <label for="usa_bocinas_sala_video">
                    ¿Usará las bocinas? (multimedia, videos, étc)
                </label>
            </div>
        </div>
        <input type="submit" name="AgendarButtonSalaVideo" value="Agendar" id="AgendarButtonSalaVideo" class="btn btn-primary">
    </form>


    </div>


    <div id="Creditos" style="margin-top: 500px">          
        <h4>Universidad Autónoma de San Luis Potosí</h4>
            Álvaro Obregón #64, Col. Centro, C.P. 78000<br>
        San Luis Potosí, S.L.P. México                <br>
        Tel.  (444) 826 2300<br>
        ©<span id="lbAño">2024</span> © Todos los derechos reservados.           
   </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
<script src="js/index.js"></script>
</html>