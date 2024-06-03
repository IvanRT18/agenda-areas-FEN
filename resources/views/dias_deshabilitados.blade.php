<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reservas laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />   
    <link rel="icon" href="https://serviciosenlinea.uaslp.mx/LoginGateway/favicon.ico">
    
    {{-- Nuevo bootstrap --}}
    <link rel="stylesheet" href="https://www.uaslp.mx/Content/bootstrap.min.css">
    <script src="https://www.uaslp.mx/Scripts/bootstrap.bundle.min.js"></script>
    
    <!-- UASLP css -->
    <link href='https://serviciosenlinea.uaslp.mx/LoginGateway/Content/UASLP.css' rel="stylesheet" />
    
    {{-- Flatpickr css --}}
    <link rel="stylesheet" href="/js/flatpickr/flatpickr.min.css">

    {{-- Timepicker css --}}
    <link rel="stylesheet" href="{{ asset('js/timepicker/jquery.timepicker.css') }}">
    <link rel="stylesheet" href={{asset('css/agendaLabs.css')}}>
</head>
<body>
    <div class="container">

        {{-- Header --}}
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

        {{-- Navbar  --}}
        <nav class="navbar navbar-expand-lg navbar-dark fs-6" style="background-color: #1B3257;">
            <div class="container-fluid">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ url('inicio') }}">Inicio</a>
                  </li>
          
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Usuarios
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="{{ url('administrador/usuarios/registrar') }}">Registrar usuario</a></li>
                      <li><a class="dropdown-item" href="{{ url('administrador/usuarios') }}">Ver usuarios</a></li>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Editar reservaciones
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="{{ url('administrador/reservaciones/auditorio') }}">Auditorio</a></li>
                      <li><a class="dropdown-item" href="{{ url('administrador/reservaciones/aula-magna') }}">Aula Magna</a></li>
                      <li><a class="dropdown-item" href="{{ url('administrador/reservaciones/laboratorio') }}">Laboratorio</a></li>
                      <li><a class="dropdown-item" href="{{ url('administrador/reservaciones/sala-videoconferencia') }}">Sala de videoconferencias</a></li>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Mostrar horas reservadas
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="{{ url('horas-reservadas/auditorio') }}">Auditorio</a></li>
                      <li><a class="dropdown-item" href="{{ url('horas-reservadas/aula-magna') }}">Aula Magna</a></li>
                      <li><a class="dropdown-item" href="{{ url('horas-reservadas/laboratorio') }}">Laboratorio</a></li>
                      <li><a class="dropdown-item" href="{{ url('horas-reservadas/sala-videoconferencia') }}">Sala de videoconferencias</a></li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('administrador/deshabilitar-dias') }}">Deshabilitar días</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/logout') }}">Cerrar sesión</a>
                  </li>
                </ul>
              </div>
            </div>
          </nav>

          @if (session('status'))
            <h6 class="alert alert-success">{{ session('status') }}</h6>
        @endif
        <div class="d-flex my-4 justify-content-end ">
            <a href="{{ url('administrador') }}" class="btn btn-danger float-end">Regresar</a>
        </div>
        <div class="d-flex flex-column flex-lg-row justify-content-around">
            <div class="flex-fill mb-3 mb-lg-0">
              <div class="d-flex justify-content-around">
                <p class="fs-4 text-center">Días deshabilitados</p>
                <a href="{{ url('administrador/deshabilitar-dias/habilita-todos-dias') }}" class="btn bg-success btn-sm text-white fs-6" style="width: 50%; height: min-content;">Habilitar todos</a>
              </div>
              <div>
                @foreach ($diasDeshabilitados as $diaDeshabilitado)
                  <div class="bg-secondary p-2 text-white bg-opacity-75 mb-2 d-flex justify-content-around align-items-center rounded-2">
                    <div class="">
                      <p class="fs-6" style="margin-bottom: 0px;">
                        {{$diaDeshabilitado->diaDeshabiliado}}
                      </p>
                    </div>
                    <a href="{{ url('administrador/deshabilitar-dias/habilita-dia/'. $diaDeshabilitado->id) }}" class="btn bg-success btn-sm text-white fs-6">Habilitar</a>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="flex-fill">
              <p class="fs-4 text-center">Deshabilitar nuevo día</p>
              <form action="{{ url('administrador/deshabilitar-dias/agregar-dia') }}" method="POST" class="d-flex flex-column align-items-center">
                @csrf
                <input type="date" id="agregarDiaDesactivadoInput" name="agregarDiaDesactivadoInput">
                <button type="submit" class="btn btn-primary mt-4">Guardar cambios</button>
              </form>
            </div>
          </div>
          
    </div>
   
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('js/dias_deshabilitados.js') }}"></script>
</html>