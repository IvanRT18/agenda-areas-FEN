<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">
    
    {{-- Nuevo bootstrap --}}
    <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">
    <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>
    
    <!-- UASLP -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
    <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}> 

    <style>
        .sortable-header {
            cursor: pointer;
        }
        .sort-icon {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 5px;
            vertical-align: middle;
            border-top: 4px solid;
            border-right: 4px solid transparent;
            border-bottom: none;
            border-left: 4px solid transparent;
        }
        .sort-icon.asc {
            border-top: none;
            border-right: 4px solid transparent;
            border-bottom: 4px solid;
            border-left: 4px solid transparent;
        }

        .sort-icon.desc {
            background-color: blueviolet;
            border-top: 4px solid;
            border-right: 4px solid transparent;
            border-bottom: none;
            border-left: 4px solid transparent;
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

        {{-- Alerta --}}
        @if (session('status'))
            <h6 class="alert alert-success">{{ session('status') }}</h6>
        @endif

        {{-- Contenido  --}}
        <div class="container-fluid d-inline-flex justify-content-between mt-3">
            <a href="{{ url('dias-inhabilitados-laboratorio') }}" class="btn btn-info btn-sm fs-6">Días inhabilitados</a>

            <a href="{{ url('registrar-usuario') }}" class="btn btn-success btn-sm fs-6">Registrar nuevo usuario</a>

            {{-- Modal ver horas reservadas de área --}}
            <button type="button" class="btn btn-danger fs-6" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                Reservaciones
            </button>

            <!-- Modal selecciona área horas reservadas -->
            <button type="button" class="btn btn-danger fs-6" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Mostrar horas reservadas
            </button>

            {{-- Cerrar sesión --}}
            <form action="{{url('logout')}}">
                <input type="submit" value="Cerrar Sesión" class="btn btn-primary">
            </form>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Seleccione el área</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="flex flex-column">
                                <a href="{{ url('horas-reservadas/auditorio') }}" class="btn btn-success btn-sm fs-6 mb-3 ">Auditorio</a>
                                <a href="{{ url('horas-reservadas/aula-magna') }}" class="btn btn-success btn-sm fs-6 mb-3">Aula Magna</a>
                                <a href="{{ url('horas-reservadas/laboratorio') }}" class="btn btn-success btn-sm fs-6 mb-3">Laboratorio de cómputo</a>
                                <a href="{{ url('horas-reservadas/sala-videoconferencias') }}" class="btn btn-success btn-sm fs-6 mb-3">Sala de videoconferencia</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel2">Seleccione el área</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="flex flex-column">
                                <a href="{{ url('administrador/reservaciones/auditorio') }}" class="btn btn-success btn-sm fs-6 mb-3 ">Auditorio</a>
                                <a href="{{ url('administrador/reservaciones/aula-magna') }}" class="btn btn-success btn-sm fs-6 mb-3">Aula Magna</a>
                                <a href="{{ url('administrador/reservaciones/laboratorio') }}" class="btn btn-success btn-sm fs-6 mb-3">Laboratorio de cómputo</a>
                                <a href="{{ url('administrador/reservaciones/sala-videoconferencias') }}" class="btn btn-success btn-sm fs-6 mb-3">Sala de videoconferencia</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @yield('table')
        

        <!-- Modal -->
        <div class="modal fade modal-dialog modal-dialog-scrollable" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                ...
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
        </div>

    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/adminLab.js"></script>

</html>