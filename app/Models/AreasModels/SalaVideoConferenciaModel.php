<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaVideoConferenciaModel extends Model
{
    use HasFactory;
    
    protected $table = 'sala_videoconferencia_reservaciones';

    protected $fillable = [
        'Rpe',
        'usaProyector',
        'fechaReservada',
        'horaReservadaInicio',
        'horaReservadaFinal',
        'numeroMicrofonos',
        'usaComputadora',
        'usaBocinas',
    ];
}
