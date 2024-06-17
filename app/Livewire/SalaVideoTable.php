<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AreasModels\SalaVideoConferenciaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class SalaVideoTable extends DataTableComponent
{
    protected $model = SalaVideoConferenciaModel::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchPlaceholder('Buscar reservación...');
        $this->setSingleSortingDisabled();
        $this->setDefaultSort('fecha_reservada', 'desc');
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Fecha reservada')
                ->filter(function($query, $value) {
                    if ($value) {
                        $query->whereDate('fecha_reservada', $value);
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
        SalaVideoConferenciaModel::whereIn('id', $this->selected)->delete();
        $this->selected = [];
    }

    public function columns(): array
    {
        $area = Request::segment(3);

        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make("RPE", "rpe")
                ->sortable()->searchable(),
            Column::make("Nombre completo")
            ->label(fn($row) => $row->usuario->getNombreCompleto())
            ->sortable()
            ->searchable(function(Builder $query, $searchTerm) {
                $query->orWhereHas('usuario', function($query) use ($searchTerm) {
                    $query->whereRaw("CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ["%{$searchTerm}%"])
                            ->orWhereRaw("CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombres) LIKE ?", ["%{$searchTerm}%"]);;
                });
            }),
            Column::make("Fecha reservada", "fecha_reservada")
                ->sortable()->searchable(),
            Column::make("Hora inicio", "hora_reserva_inicio")
                ->sortable(),
            Column::make("Hora final", "hora_reserva_final")
                ->sortable(),
            BooleanColumn::make("Usa proyector", "usa_proyector")
                ->sortable(),
            Column::make("Numero microfonos", "numero_microfonos")
                ->sortable(),
            BooleanColumn::make("Usa computadora", "usa_computadora")
                ->sortable(),
            BooleanColumn::make("Usa bocinas", "usa_bocinas")
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
        $reservacion = SalaVideoConferenciaModel::find($id);
        if ($reservacion) {
            $reservacion->delete();
            Session::flash('status', 'Reservacion eliminada con éxito');
        }
    }
}
