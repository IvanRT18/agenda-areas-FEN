<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaMagnaModel extends Model
{
    use HasFactory;
    
    protected $table = 'aula_magna_reservaciones';

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
