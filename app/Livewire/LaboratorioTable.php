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
        ];
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
            // Column::make("Nombre completo")
            // ->label(fn($row) => $row->nombre_completo)
            // ->sortable()
            // ->searchable(function(Builder $query, $searchTerm) {
            //     $query->orWhereHas('usuario', function($query) use ($searchTerm) {
            //         $query->where('apellido_paterno', 'like', "%{$searchTerm}%")
            //                 ->orWhere('apellido_materno', 'like', "%{$searchTerm}%")
            //                 ->orWhere('nombres', 'like', "%{$searchTerm}%");
            //     });
            // }),
            // Column::make("Nombre completo")
            //     ->label(fn($row) => $row->nombre_completo)
            //     ->sortable()
            //     ->searchable(function(Builder $query, $searchTerm) {
            //         $terms = explode(' ', $searchTerm);

            //         $query->orWhereHas('usuario', function($query) use ($terms) {
            //             foreach ($terms as $term) {
            //                 $query->where(function($query) use ($term) {
            //                     $query->where('apellido_paterno', 'like', "%{$term}%")
            //                           ->orWhere('apellido_materno', 'like', "%{$term}%")
            //                           ->orWhere('nombres', 'like', "%{$term}%");
            //                 });
            //             }
            //         });
            //     }),
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
            Session::flash('status', 'Reservaci
            on eliminada con éxito Session');
        }
    }
}
