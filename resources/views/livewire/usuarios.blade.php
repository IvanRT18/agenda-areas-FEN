<div>
    {{-- Be like water. --}}
    <input type="text" wire:model.debounce.300ms="search" placeholder="Buscar usuarios..." class="form-control mb-3">

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Clave</th>
                <th>Apellido paterno</th>
                <th>Apellido materno</th>
                <th>Nombres</th>
                <th>Tipo</th>
                <th>Correo</th>
                <th>Editar / Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{$usuario->id}}</td>
                    <td>{{$usuario->clave}}</td>
                    <td>{{$usuario->apellido_paterno}}</td>
                    <td>{{$usuario->apellido_materno}}</td>
                    <td>{{$usuario->nombres}}</td>
                    <td>{{$usuario->tipo}}</td>
                    <td>{{$usuario->correo}}</td>
                    <td>
                        <a href="{{ url('administrador/usuarios/editar/'.$usuario->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <a href="{{ url('administrador/usuarios/eliminar/'.$usuario->id) }}" class="btn btn-danger btn-sm">Borrar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $usuarios->links() }}
</div>
