<div class="d-flex flex-column gap-2">
  <a href="{{ url('administrador/editar-reservacion/' . $area . '/' . $usuarioId) }}" class="btn btn-sm btn-primary">Editar</a>
  <button wire:click="delete({{ $usuarioId }})" class="btn btn-sm btn-danger">Eliminar</button>
</div>