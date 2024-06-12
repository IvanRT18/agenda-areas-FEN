<?php

namespace App\Models\AreasModels;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioModel extends Model
{
    use HasFactory;
    
    protected $table = 'laboratorio_reservaciones';

    protected $fillable = [
        'rpe',
        'fecha_reservada',
        'hora_reserva_inicio',
        'hora_reserva_final',
        'numero_equipos',
        'usa_proyector',
        'req_adicionales',
        'materia',
        'actividad',
        'nombre_curso'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'rpe', 'clave');
    }

    // Accesor para obtener el nombre completo del usuario
    public function getNombreCompletoAttribute()
    {
        return $this->usuario ? $this->usuario->getNombreCompleto() : 'N/A';
    }
}
