<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaMagnaConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'aula_magna_configuracion';

    protected $fillable = [
        'numero_microfonos'
    ];
}
