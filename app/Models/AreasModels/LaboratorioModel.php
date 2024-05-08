<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioModel extends Model
{
    use HasFactory;
    
    protected $table = 'laboratorio_reservaciones';

    protected $fillable = [
        'rpe',
        'nombre_completo',
        'fecha_reservada',
        'hora_reserva_inicio',
        'hora_reserva_final',
        'numero_equipos',
        'usa_proyector',
        'req_adicionales',
        'materia',
        'actividad',
        'nombre_curso'
    ];
}
