<?php

namespace App\Models\AreasModels;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaMagnaModel extends Model
{
    use HasFactory;
    
    protected $table = 'aula_magna_reservaciones';

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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'rpe', 'clave');
    }

    public function getNombreCompletoAttribute()
    {
        return $this->usuario ? $this->usuario->getNombreCompleto() : 'N/A';
    }
}
