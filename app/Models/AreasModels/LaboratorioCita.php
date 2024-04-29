<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioCita extends Model
{
    use HasFactory;
    
    protected $table = 'laboratorio_reservaciones';

    protected $fillable = [
        'numEquipos',
        'fechaReservada',
        'horaReservaInicio',
        'horaReservaFinal',
        'usaProyector',
        'reqAdicionales',
        'Rpe',
        'nombreCompleto',
        'materia',
        'actividad',
        'nombreCurso'
    ];
}
