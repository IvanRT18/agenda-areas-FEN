<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reservas laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">

    {{-- @vite('resources/css/app.css') --}}
    @vite('resources/js/app.js')
    <!-- Bootstrap -->
    {{-- <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/bootstrap.min.css' rel="stylesheet" />  --}}
    
    {{-- Nuevo bootstrap --}}
    <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">
    <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>
    
    <!-- UASLP -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
    <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}> 
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
                        <span id="Titulo">Agenda de áreas - Fac. Enfermería y Nutrición</span>
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

    </div>
        

    <div class="container">
        @if (session('status'))
            <h6 class="alert alert-success">{{ session('status') }}</h6>
        @endif
        <div class="d-flex my-4 justify-content-end ">
            <a href="{{ url('admin-reservas-laboratorio') }}" class="btn btn-danger float-end">Regresar</a>
        </div>
        <div class="d-flex justify-content-around ">
            <div class="col-3">
                <p class="fs-4 text-center ">Días deshabilitados</p>
                {{-- <div class="form-group mb-3">
                    <input type="date" id="diasDeshabilitadosInput" name="diasDeshabilitadosInput">

                    <p>YYYY-MM-DD</p>
                    
                </div> --}}

                @foreach ($diasDeshabilitados as $diaDeshabilitado)
                    <div class="bg-secondary p-2 text-white bg-opacity-75 mb-2 d-flex justify-content-around align-items-center rounded-2">
                        <div class="">
                            <p style="
                            margin-bottom: 0px;
                        " class="fs-6">
                                {{$diaDeshabilitado->diaDesInicio}}
                            </p>
                        </div>
                        <a href="{{ url('elimina-dia-deshabilitado-laboratorio/'. $diaDeshabilitado->id) }}" class="btn bg-success btn-sm text-white fs-6">Habilitar</a>
                    </div>
                @endforeach
                
            </div>

            <div>
                <p class="fs-4 text-center">Deshabilitar nuevo día</p>
                <form action="{{ url('/agrega-dia-inhabilitado-laboratorio') }}" method="POST" class="d-flex flex-column align-items-center ">
                    @csrf

                    <input type="date" id="agregarDiaDesactivadoInput" name="agregarDiaDesactivadoInput">

                    <button type="submit" class="btn btn-primary mt-4 ">Deshabilitar día</button>
                </form>
            </div>
        </div>
    </div>
   
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
<script src="js/diasDesactivados.js"></script>
</html>