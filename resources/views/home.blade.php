<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Agenda de laboratorios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="favicon.ico">

    @vite('resources/css/app.css')


    <!-- Bootstrap -->
    {{-- <link href={{asset('css/bootstrap.min.css')}} rel="stylesheet" />  --}}
    <!-- UASLP -->
    {{-- <link href={{asset('css/UASLP.css')}} rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}>  --}}
</head>

<body class="lg:container mx-auto">
    {{-- Header --}}
    <header class="bg-primary h-40 flex w-full">
        {{-- Escudos --}}
        <div class="flex w-1/2 items-center">
            {{-- Escudo UASLP --}}
            <div class="h-20 w-1/2 mx-4 ">
                <img src="/img/UASLP.png" alt="Escudo UASLP" class="bg-center bg-cover h-full">
            </div>
            {{-- Linea Escudo --}}
            <div class="border border-white h-20 mx-1">

            </div>
            
            {{-- Titulo --}}
            <div class="w-1/2">
                <p class="text-white text-xl align-middle">Inicio de Sesión Institucional</p>
            </div>
        </div>
        {{-- Foto edificio central --}}
        <div class="flex w-1/2 h-full">
                <img src="/img/edificio.jpg" alt="Edificio UASLP" class="h-full bg-center bg-cover">
        </div>
    </header>

    <div class="">
        
            <h1>Agenda de áreas comúnes</h1>
            <h1>{{$_GET["user"]}}</h1>
       
        {{-- Checkboxes de areas para reservar --}}
       <div class="flex contenido">
        <div class="checkbox-container col-xs-6">
            <label for="checkboxAuditorio"><input type="checkbox" name="op1" id="checkboxAuditorio">Auditorio</label>
            <label for="checkboxAulaMagna"><input type="checkbox" name="op2" id="checkboxAulaMagna">Aula magna (propedéutico)</label>
            <label for="checkboxLabComp"><input type="checkbox" name="op3" id="checkboxLabComp">Laboratorio de cómputo</label>
            <label for="checkboxSalaVideo"><input type="checkbox" name="op4" id="checkboxSalaVideo">Sala de videoconferencia</label>
       </div>
       {{-- Formulario --}}
       <div class="formulario-container col-xs-6 ">
        <form action="" class="flex flex-col col-xs-12 gap">
            <div>
                <label for="numEquipos">¿Cuantos equipos de cómputo usará?</label>
            <div class="flex flex-col">
                <input id="numEquipos" type="number" name="numEquipos" min="1" max="40" class="col-xs-4" required/>
            </div>
            </div>
            
            <div class="flex flex-col">
                <label for="start">Fecha para reservar:</label>
            <input type="date" id="fecha" name="fecha" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+15 days')) }}" class="col-xs-4" required>
            </div>
            
            <div>
                <label for="checkboxProyector"><input type="checkbox" name="opProyetor" id="checkboxProyector">¿Usará el proyector del laboratorio?</label>
            </div>

            <div class="flex flex-col">
                <strong>Opcional:</strong>
            <label for="textareaOpcional">¿Necesita de algo en particular? (Software, cañones, bocinas, étc)</label>
            <textarea name="textareaOpcional" id="textareaOpcional" rows="5" style="resize: none"></textarea>
            </div>
            
            <label for="RPEInput">RPE</label>
            <input type="text" name="RPEInput" id="RPEInput">

            <label for="NombreComInput">Nombre completo</label>
            <input type="text" name="NombreComInput" id="NombreComInput">

            <label for="MateriaInput">Materia</label>
            <input type="text" name="MateriaInput" id="MateriaInput">

            <label for="ActividadInput">Actividad a realizar (clase/curso/examen/otro)</label>
            <input type="text" name="ActividadInput" id="ActividadInput">

            <label for="NombreActInput">Nombre del curso a realizar</label>
            <input type="text" name="NombreActInput" id="NombreActInput">

            <input type="submit" value="Save" />
        </form>
       </div>
       </div>
    </main>
    
</body>

</html>