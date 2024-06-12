<div class="d-flex justify-content-around align-items-center">
    {{-- <button wire:click="habilitaDia({{ $diaDeshabilitado->id }})" class="btn bg-success btn-sm text-white fs-6">Habilitar</button> --}}
    <a href="{{ url('administrador/deshabilitar-dias/habilita-dia/'. $diaDeshabilitado->id) }}" class="btn bg-success btn-sm text-white fs-6">Habilitar</a>
</div>
