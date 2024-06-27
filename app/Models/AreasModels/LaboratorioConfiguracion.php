<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'laboratorio_configuracion';

    protected $fillable = [
        'numero_computadoras',
        'parametro_adelante',
    ];
}
