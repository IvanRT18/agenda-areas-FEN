<?php

namespace App\Models\AreasModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditorioConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'auditorio_configuracion';

    protected $fillable = [
        'numero_microfonos'
    ];
}
