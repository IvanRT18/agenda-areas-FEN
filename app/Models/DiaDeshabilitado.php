<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaDeshabilitado extends Model
{
    use HasFactory;
    protected $table = 'dias_deshabilitados';
    protected $fillable = [
        'diaDeshabiliado',
    ];
}
