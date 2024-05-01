<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaVideoConferenciaModel extends Model
{
    use HasFactory;
    
    protected $table = 'sala_videoconferencia_reservaciones';

    protected $fillable = [
        'rpe',
        'fecha_reservada',
        'hora_reservada_inicio',
        'hora_reservada_final',
        'usa_proyector',
        'numero_microfonos',
        'usa_computadora',
        'usa_bocinas',
    ];
}
