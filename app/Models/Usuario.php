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

    /**
     * Determine si el usuario es un administrador.
     *
     * @return bool
     */
    public function esAdministrador()
    {
        return $this->tipo === 'administrador';
    }

    /**
     * Devuelve el nombre completo del usuario autenticado
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->apellido_paterno . " " . $this->apellido_materno . " ". $this->nombres;
    }

    /**
     * Devuelve el rpe del usuario
     *
     * @return number
     */
    public function getRpe()
    {
        return $this->clave;
    }
}
