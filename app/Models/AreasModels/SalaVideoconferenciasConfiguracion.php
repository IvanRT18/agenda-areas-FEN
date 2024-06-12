<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaVideoconferenciasConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'sala_videoconferencias_configuracion';

    protected $fillable = [
        'numero_microfonos'
    ];
}
