<div class="btn-group" role="group" aria-label="Acciones">
    <a href="{{ url('administrador/editar-reservacion/' . $area . "/". $usuarioId) }}" class="btn btn-sm btn-primary">Editar</a>
    <button wire:click="delete({{ $usuarioId }})" class="btn btn-sm btn-danger">Eliminar</button>
      {{-- <button wire:click="delete({{ $usuarioId }})" class="btn btn-danger fs-6" data-bs-toggle="modal" data-bs-target="#deleteModal"> --}}
</div>
