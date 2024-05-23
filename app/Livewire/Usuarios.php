<?php

namespace App\Livewire;

use App\Models\Usuario;
use Livewire\Component;
use Livewire\WithPagination;

class Usuarios extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 5;
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $usuarios = Usuario::where('nombres', 'like', '%'. $this->search . '%')->orWhere('correo', 'like', '%' . $this->search . '%')->paginate($this->perPage);
        return view('livewire.usuarios', ['usuarios' => $usuarios]);
    }
}
