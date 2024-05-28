<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AreasModels\LaboratorioModel;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class LaboratorioTable extends DataTableComponent
{
    protected $model = LaboratorioModel::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchPlaceholder('Buscar reservación...');
        $this->setSingleSortingDisabled();
        $this->setColumnSelectStatus(false);
        $this->setDefaultSort('fecha_reservada', 'desc');
    }

    public function query()
    {
        return LaboratorioModel::with('usuario'); // Asegúrate de cargar la relación 'usuario'
    }

    public function columns(): array
    {
        $area = Request::segment(3);

        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make("RPE", "rpe")
                ->sortable()->searchable(),
            // Column::make("Nombre completo", "nombre_completo")
            //     ->sortable(),
             Column::make('Nombre', 'usuario.nombres')->sortable()->searchable(),
            Column::make("Fecha reservada", "fecha_reservada")
                ->sortable()->searchable(),
            Column::make("Hora inicio", "hora_reserva_inicio")
                ->sortable(),
            Column::make("Hora final", "hora_reserva_final")
                ->sortable(),
            Column::make("Numero equipos", "numero_equipos")
                ->sortable(),
            BooleanColumn::make("Usa proyector", "usa_proyector")
                ->sortable(),
            Column::make("Req. adicionales", "req_adicionales")
                ->sortable(),
            Column::make("Materia", "materia")
                ->sortable()->searchable(),
            Column::make("Actividad", "actividad")
                ->sortable()->searchable(),
            Column::make("Nombre curso", "nombre_curso")
                ->sortable()->searchable(),
            Column::make('Acciones')->label(function($row) use ($area) {
                if ($area === null) {
                    $area = 'laboratorio';
                }
                return view('livewire.areas-actions-buttons', [
                    'usuarioId' => $row->id,
                    'area' => $area,
                ]);
            })->html(),
        ];
    }

    public function delete($id)
    {
        $reservacion = LaboratorioModel::find($id);
        if ($reservacion) {
            $reservacion->delete();
            Session::flash('status', 'Reservacion eliminada con éxito');
        }
    }
}
