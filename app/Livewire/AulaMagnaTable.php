<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AreasModels\AulaMagnaModel;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class AulaMagnaTable extends DataTableComponent
{
    protected $model = AulaMagnaModel::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchPlaceholder('Buscar reservación...');
        $this->setSingleSortingDisabled();
    }

    public function columns(): array
    {
        $area = Request::segment(3);

        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make("RPE", "rpe")
                ->sortable()->searchable(),
            Column::make("Fecha reservada", "fecha_reservada")
                ->sortable()->searchable(),
            Column::make("Hora inicio", "hora_reserva_inicio")
                ->sortable(),
            Column::make("Hora final", "hora_reserva_final")
                ->sortable(),
            Column::make("Usa proyector", "usa_proyector")
                ->sortable(),
            Column::make("Numero microfonos", "numero_microfonos")
                ->sortable(),
            Column::make("Usa computadora", "usa_computadora")
                ->sortable(),
            Column::make("Usa bocinas", "usa_bocinas")
                ->sortable(),
            Column::make('Acciones')->label(function($row) use ($area) {
                return view('livewire.areas-actions-buttons', [
                    'usuarioId' => $row->id,
                    'area' => $area,
                ]);
            })->html(),
        ];
    }

    public function delete($id)
    {
        $reservacion = AulaMagnaModel::find($id);
        if ($reservacion) {
            $reservacion->delete();
            Session::flash('status', 'Reservacion eliminada con éxito');
        }
    }
}
