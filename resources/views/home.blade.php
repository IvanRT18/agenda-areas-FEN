<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Agenda de laboratorios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="favicon.ico">

    {{-- @vite('resources/css/app.css') --}}


    <!-- Bootstrap -->
    <link href={{asset('css/bootstrap.min.css')}} rel="stylesheet" /> 
    <!-- UASLP -->
    <link href={{asset('css/UASLP.css')}} rel="stylesheet" />
    <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}> 
</head>

<body>
    <div class="container">
        <div class="Encabezado">
            <div class="escudos">
                <div class="escudo escudoUASLP">
                    <a href={{url('http://www.uaslp.mx')}}>
                        <img id="UASLPLogo" src={{asset('img/UASLP.png')}} class="img-responsive">
                        <img id="UASLPLogoMobile" src={{asset('img/UASLPVertical.png')}} class="img-responsive">
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
            <div class="LemaAzul"><img src={{asset('img/amarillo_azul.png')}}></div>
            <div class="LemaAmarillo">"Siempre autónoma. Por mi patria educaré."</div>
        </div>
    
        <div>
            <h1>Agenda de áreas comúnes</h1>
            <h1>{{$_GET["user"]}}</h1>

            @if(session('status'))
            <div>
                {{ session('status') }}
            </div>
          @endif
        </div>

        {{-- Checkboxes de areas para reservar --}}
       <div class="flex flex-col contenido gap align-center">
        <h1>Área para reservar:</h1>
        <div class="radio-container col-xs-6" id="radioContainer">
            <label for="checkboxAuditorio"><input type="radio" name="chAreas" id="checkboxAuditorio">Auditorio</label>
            <label for="checkboxAulaMagna"><input type="radio" name="chAreas" id="checkboxAulaMagna">Aula magna (propedéutico)</label>
            <label for="checkboxLabComp"><input type="radio" name="chAreas" id="checkboxLabComp">Laboratorio de cómputo</label>
            <label for="checkboxSalaVideo"><input type="radio" name="chAreas" id="checkboxSalaVideo">Sala de videoconferencia</label>
       </div>

       {{-- Formulario Laboratorio de cómputo--}}
       <form name="" method="POST" class="formulario-container col-xs-6 mb-4" action="{{url('guarda-auditorio-cita')}}" >
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
            </div>

            <div class="flex flex-col">
                <label for="horaReservada">Hora para reservar:</label>
                <div class="col-12">
                    <input type="time" id="horaReservada" name="horaReservada" min="07:00" max="20:00" class="w-100" required>
                    <span class="validity"></span>
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
                <input type="text" name="Rpe" id="Rpe">
            </div>

            <div class="flex flex-col">
                <label for="nombreCompleto">Nombre completo</label>
                <input type="text" name="nombreCompleto" id="nombreCompleto">
            </div>

            <div class="flex flex-col">
                <label for="materia">Materia</label>
                <input type="text" name="materia" id="materia">
            </div>

            <div class="flex flex-col">
                <label for="actividad">Actividad a realizar (clase/curso/examen/otro)</label>
                <input type="text" name="actividad" id="actividad">
            </div>

            <div class="flex flex-col">
                <label for="nombreCurso">Nombre del curso a realizar</label>
                <input type="text" name="nombreCurso" id="nombreCurso">
            </div>

            <input type="submit" name="AgendarButton" value="Agendar" id="AgendarButton" class="btn btn-primary">
        </div>
    </form>

    {{-- Formulario auditorio --}}
    {{-- <form name="" method="POST" class="formulario-container col-xs-6 mb-4" action="{{url('guarda-auditorio-cita')}}">
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

    </form> --}}

    </div>


    <div id="Creditos">          
        <h4>Universidad Autónoma de San Luis Potosí</h4>
            Álvaro Obregón #64, Col. Centro, C.P. 78000<br>
        San Luis Potosí, S.L.P. México                <br>
        Tel.  (444) 826 2300<br>
        ©<span id="lbAño">2024</span> © Todos los derechos reservados.           
   </div>
</body>
<script src="js/index.js"></script>

</html>