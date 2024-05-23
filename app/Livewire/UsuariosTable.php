<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;

class UsuariosTable extends DataTableComponent
{
    protected $model = Usuario::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchPlaceholder('Buscar usuario...');
        $this->setSearchEnabled();
        $this->setSingleSortingDisabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()->searchable(),
            Column::make("Clave", "clave")
                ->sortable()->searchable(),
            Column::make("Apellido paterno", "apellido_paterno")
                ->sortable()->searchable(),
            Column::make("Apellido materno", "apellido_materno")
                ->sortable()->searchable(),
            Column::make("Nombres", "nombres")
                ->sortable()->searchable(),
            Column::make("Tipo", "tipo")
                ->sortable()->searchable(),
            Column::make("Correo", "correo")
                ->sortable()->searchable(),
            Column::make('Acciones')
            ->label(function ($row) {
                return view('livewire.user-actions-buttons', ['usuarioId' => $row->id]);
            })
            ->html(),
        ];
    }

    public function delete($id)
    {
        $usuario = Usuario::find($id);
        if ($usuario) {
            $usuario->delete();
            Session::flash('status', 'Usuario eliminado con éxito');
        }
    }
}
