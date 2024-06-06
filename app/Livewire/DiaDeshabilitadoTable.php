<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\DiaDeshabilitado;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class DiaDeshabilitadoTable extends DataTableComponent
{
    protected $model = DiaDeshabilitado::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('dias_deshabilitado', 'desc');
        $this->setSearchPlaceholder('Buscar reservación...');
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Fecha reservada')
                ->filter(function($query, $value) {
                    if ($value) {
                        $query->whereDate('dias_deshabilitado', $value);
                    }
                }),
        ];
    }

    public function bulkActions(): array
    {
        return [
            'deleteSelected' => 'Eliminar',
        ];
    }

    public function deleteSelected()
    {
        DiaDeshabilitado::whereIn('id', $this->selected)->delete();
        $this->selected = [];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Dias deshabilitados", "dias_deshabilitado")
                ->sortable()->searchable(),
                Column::make('Acciones')
                ->label(function ($row) {
                    return view('livewire.dias-deshabilitados-actions-buttons', ['diaDeshabilitado' => $row]);
                })
                ->html(),
        ];
    }

    public function habilitaDia($id)
    {
        $dia = DiaDeshabilitado::find($id);
        if ($dia) {
            // Lógica para habilitar el día
            $dia->delete(); // Por ejemplo, eliminar el registro para habilitar el día
        }
    }

    public function habilitaTodosDias()
    {
        DiaDeshabilitado::query()->delete();
    }
}
