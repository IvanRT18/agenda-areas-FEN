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
    
    {{-- Timepicker css --}}
    <link rel="stylesheet" href="{{ asset('js/timepicker/jquery.timepicker.css') }}">
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
            <h1>
                {{session('user')}}
            </h1>
    
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
       <form name="formularioLabComp" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('guarda-auditorio-cita')}}" id="formularioLabComp">
           @csrf
        <div class="flex flex-col col-xs-12 gap">
            <div>
                <label for="numEquipos">¿Cuantos equipos de cómputo usará?</label>
            <div class="flex flex-col">
                <input id="numEquipos" type="number" name="numEquipos" min="1" max="40" required/>
            </div>
            </div>

            
            
            <div class="flex flex-col">
                <label for="fechaReservada">Fecha para reservar:</label>
            <input type="date" id="fechaReservada" name="fechaReservada" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+14 days')) }}" required>

            <span id="pantallaCarga" class="hidden"> <p>Cargando horas reservadas...</p></span>
            
            </div>

            <div class="flex flex-col">
                <label for="horaReservaInicio">Hora para reservar:</label>
                <div class="col-12">
                    {{-- <input type="time" id="horaReservaInicio" name="horaReservaInicio" min="07:00" max="20:00" class="w-100" required> --}}
                    <input type="text" id="horaReservaInicio" name="horaReservaInicio" class="w-100" required/>
                    {{-- <span class="validity"></span> --}}
                </div>
            </div>

            <div class="flex flex-col">
                <label for="horaReservaFinal">A</label>
                <div class="col-12">
                    {{-- <input type="time" id="horaReservaFinal" name="horaReservaFinal" min="07:00" max="20:00" class="w-100" required> --}}
                    <input type="text" id="horaReservaFinal" name="horaReservaFinal" class="w-100" required/>
                    {{-- <span class="validity"></span> --}}
                </div>
            </div>
            
            <div>
                <label for="usaProyector"><input type="checkbox" name="usaProyector" id="usaProyector" value="true">¿Usará el proyector del laboratorio? (Opcional)</label>
            </div>

            <div class="flex flex-col">
                <strong>Opcional:</strong>
            <label for="reqAdicionales">¿Necesita de algo en particular? (Software, cañones, bocinas, étc)</label>
            <textarea name="reqAdicionales" id="reqAdicionales" rows="5" style="resize: none"></textarea>
            </div>
            
            <div class="flex flex-col">
                <label for="Rpe">RPE</label>
                <input type="text" name="Rpe" id="Rpe" required>
            </div>

            <div class="flex flex-col">
                <label for="nombreCompleto">Nombre completo</label>
                <input type="text" name="nombreCompleto" id="nombreCompleto" required>
            </div>

            <div class="flex flex-col">
                <label for="materia">Materia</label>
                <input type="text" name="materia" id="materia" required>
            </div>

            <div class="flex flex-col">
                <label for="actividad">Actividad a realizar (clase/curso/examen/otro)</label>
                <input type="text" name="actividad" id="actividad" required>
            </div>

            <div class="flex flex-col">
                <label for="nombreCurso">Nombre del curso a realizar</label>
                <input type="text" name="nombreCurso" id="nombreCurso" required>
            </div>


            <input type="submit" name="AgendarButton" value="Agendar" id="AgendarButton" class="btn btn-primary">
        </div>
    </form>

    {{-- Formulario auditorio --}}
    <form name="formAuditorio" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('guarda-auditorio-cita')}}" id="formAuditorio">
        <div>
            <input type="checkbox" name="proyector" id="proyector">
            <label for="proyector">¿Usará el proyector?</label>
        </div>

        <div class="flex flex-col">
            <label for="microfonos">
                ¿Cuantos microfonos usará?
            </label>
            <input type="number" name="microfonos" id="microfonos" min="0" max="2">
        </div>

        <div>
            <input type="checkbox" name="computadora" id="computadora">
            <label for="computadora">¿Usará la computadora?</label>
        </div>

        <div>
            <input type="checkbox" name="audio" id="audio">
            <label for="audio">
                ¿Usará las bocinas? (multimedia, videos, étc)
            </label>
        </div>

        <div>
            <label for=""></label>
        </div>

    </form>

    {{-- Formulario aula magna --}}
    <form name="formAulaMagna" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('guarda-auditorio-cita')}}" id="formAulaMagna">
        <div>
            <input type="checkbox" name="proyector" id="proyector">
            <label for="proyector">¿Usará el proyector?</label>
        </div>

        <div class="flex flex-col">
            <label for="microfonos">
                ¿Cuantos microfonos usará?
            </label>
            <input type="number" name="microfonos" id="microfonos" min="0" max="2">
        </div>

        <div>
            <input type="checkbox" name="computadora" id="computadora">
            <label for="computadora">¿Usará la computadora?</label>
        </div>

        <div>
            <input type="checkbox" name="audio" id="audio">
            <label for="audio">
                ¿Usará las bocinas? (multimedia, videos, étc)
            </label>
        </div>

        <div>
            <label for=""></label>
        </div>

    </form>

    {{-- Formulario sala de videoconferencia --}}
    <form name="formSalaV" method="POST" class="formulario formulario-container col-md-6 mb-4 hidden" action="{{url('guarda-auditorio-cita')}}" id="formSalaV">
        <div>
            <input type="checkbox" name="proyector" id="proyector">
            <label for="proyector">¿Usará el proyector?</label>
        </div>

        <div class="flex flex-col">
            <label for="microfonos">
                ¿Cuantos microfonos usará?
            </label>
            <input type="number" name="microfonos" id="microfonos" min="0" max="2">
        </div>

        <div>
            <input type="checkbox" name="computadora" id="computadora">
            <label for="computadora">¿Usará la computadora?</label>
        </div>

        <div>
            <input type="checkbox" name="audio" id="audio">
            <label for="audio">
                ¿Usará las bocinas? (multimedia, videos, étc)
            </label>
        </div>

        <div>
            <label for=""></label>
        </div>

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