<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'clave',
        'apellido_materno',
        'apellido_paterno',
        'nombres',
        'tipo',
        'correo',
    ];
}
