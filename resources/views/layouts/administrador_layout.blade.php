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

    {{-- <style>
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
    </style> --}}

    @livewireStyles
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

        {{-- Alerta --}}
        <div class="container">
            @if (session('status'))
                <div class="flex flex-row justify-arnd alert alert-success">
                    <h6 class="">{{ session('status') }}</h6>
                    <button id="close-btn">X</button>
                </div>
            @endif
        </div>

        {{-- Contenido  --}}
        <div class="container-fluid d-inline-flex justify-content-between mt-3">
            <a href="{{ url('administrador/deshabilitar-dias') }}" class="btn btn-info btn-sm fs-6">Días inhabilitados</a>

            {{-- Modal de usuarios --}}
            <button type="button" class="btn btn-success fs-6" data-bs-toggle="modal" data-bs-target="#staticBackdrop3">
                Usuarios
            </button>

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

            {{-- Modal 2 --}}
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

            {{-- Modal 3 --}}
            <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel3" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel3">Selecciona la opción:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="flex flex-column">
                                <a href="{{ url('administrador/usuarios/registrar') }}" class="btn btn-success btn-sm fs-6 mb-3 ">Registrar</a>
                                <a href="{{ url('administrador/usuarios/') }}" class="btn btn-success btn-sm fs-6 mb-3">Ver usuarios</a>
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
    @livewireScripts
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    // Botón para cerrar la alerta que aparece cuando se edita una reservación
    $('#close-btn').on('click', function (e) {
        $(this).parent().remove();
    });
</script>
{{-- <script src="js/adminLab.js"></script>
<script src="{{asset('js/editCita.js')}}"></script> --}}

</html>