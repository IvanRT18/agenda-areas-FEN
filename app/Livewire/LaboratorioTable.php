<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AreasModels\LaboratorioModel;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class LaboratorioTable extends DataTableComponent
{
    public $area;

    protected $model = LaboratorioModel::class;

    public function mount()
    {
        $this->area = Request::segment(3);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchPlaceholder('Buscar reservación...');
        $this->setSingleSortingDisabled();
        $this->setColumnSelectStatus(false);
        $this->setDefaultSort('fecha_reservada', 'desc');
        // $this->setRefreshTime(15000);
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
            SelectFilter::make('Ubicación')
            ->options([
                '' => 'Todos',
                'Adelante' => 'Adelante',
                'Atrás' => 'Atrás',
            ])
            ->filter(function($query, $value) {
                if ($value) {
                    $query->where('ubicacion', $value);
                }
            }),
            SelectFilter::make('Actividad')
            ->options([
                '' => 'Todos',
                'Clase' => 'Clase',
                'Examen' => 'Examen',
                'Curso' => 'Curso',
                'Otro' => 'Otro',
            ])
            ->filter(function($query, $value) {
                if ($value) {
                    $query->where('actividad', $value);
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
        LaboratorioModel::whereIn('id', $this->selected)->delete();
        $this->selected = [];
    }

    public function query()
    {
        return LaboratorioModel::with('usuario'); // Asegúrate de cargar la relación 'usuario'
    }

    public function columns(): array
    {
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
            Column::make("Ubicación", "ubicacion")
                ->sortable(),
            Column::make('Acciones')->label(function($row) {
                if ($this->area === null) {
                    $this->area = 'laboratorio';
                }
                return view('livewire.areas-actions-buttons', [
                    'usuarioId' => $row->id,
                    'area' => $this->area,
                ]);
            })->html(),
        ];
    }

    public function delete($id)
    {
        $reservacion = LaboratorioModel::find($id);
        if ($reservacion) {
            $reservacion->delete();
            Session::flash('status', 'Reservaci
            on eliminada con éxito Session');
        }
    }
}
