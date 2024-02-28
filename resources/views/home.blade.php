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
        </div>

        {{-- Checkboxes de areas para reservar --}}
       <div class="flex flex-col contenido gap align-center">
        <h1>Área para reservar:</h1>
        <div class="checkbox-container col-xs-6">
            <label for="checkboxAuditorio"><input type="checkbox" name="op1" id="checkboxAuditorio">Auditorio</label>
            <label for="checkboxAulaMagna"><input type="checkbox" name="op2" id="checkboxAulaMagna">Aula magna (propedéutico)</label>
            <label for="checkboxLabComp"><input type="checkbox" name="op3" id="checkboxLabComp">Laboratorio de cómputo</label>
            <label for="checkboxSalaVideo"><input type="checkbox" name="op4" id="checkboxSalaVideo">Sala de videoconferencia</label>
       </div>

       {{-- Formulario --}}
       <div class="formulario-container col-xs-6 mb-4">
        <form action="" class="flex flex-col col-xs-12 gap">
            <div>
                <label for="numEquipos">¿Cuantos equipos de cómputo usará?</label>
            <div class="flex flex-col">
                <input id="numEquipos" type="number" name="numEquipos" min="1" max="40" required/>
            </div>
            </div>
            
            <div class="flex flex-col">
                <label for="start">Fecha para reservar:</label>
            <input type="date" id="fecha" name="fecha" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+15 days')) }}" required>
            </div>
            
            <div>
                <label for="checkboxProyector"><input type="checkbox" name="opProyetor" id="checkboxProyector">¿Usará el proyector del laboratorio?</label>
            </div>

            <div class="flex flex-col">
                <strong>Opcional:</strong>
            <label for="textareaOpcional">¿Necesita de algo en particular? (Software, cañones, bocinas, étc)</label>
            <textarea name="textareaOpcional" id="textareaOpcional" rows="5" style="resize: none"></textarea>
            </div>
            
            <div class="flex flex-col">
                <label for="RPEInput">RPE</label>
                <input type="text" name="RPEInput" id="RPEInput">
            </div>

            <div class="flex flex-col">
                <label for="NombreComInput">Nombre completo</label>
                <input type="text" name="NombreComInput" id="NombreComInput">
            </div>

            <div class="flex flex-col">
                <label for="MateriaInput">Materia</label>
                <input type="text" name="MateriaInput" id="MateriaInput">
            </div>

            <div class="flex flex-col">
                <label for="ActividadInput">Actividad a realizar (clase/curso/examen/otro)</label>
                <input type="text" name="ActividadInput" id="ActividadInput">
            </div>

            <div class="flex flex-col">
                <label for="NombreActInput">Nombre del curso a realizar</label>
                <input type="text" name="NombreActInput" id="NombreActInput">
            </div>

            <input type="submit" name="AgendarButton" value="Agendar" id="AgendarButton" class="btn btn-primary">
        </form>
       </div>

    </div>
       
   
       <div id="Creditos">          
        <h4>Universidad Autónoma de San Luis Potosí</h4>
            Álvaro Obregón #64, Col. Centro, C.P. 78000<br>
        San Luis Potosí, S.L.P. México                <br>
        Tel.  (444) 826 2300<br>
        ©<span id="lbAño">2024</span> © Todos los derechos reservados.           
   </div>
</body>

</html>