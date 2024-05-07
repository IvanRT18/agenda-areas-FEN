<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;


class Usuario extends Model implements Authenticatable
{
    use HasFactory;

    // Implementar los métodos de la interfaz Authenticatable
    public function getAuthIdentifierName()
    {
        return 'clave'; // O el campo que usas para identificar al usuario
    }

    public function getAuthIdentifier()
    {
        return $this->clave; // O el valor del campo que usas para identificar al usuario
    }

    public function getAuthPassword()
    {
        return $this->password; // El campo de la contraseña del usuario
    }
    
    public function getRememberToken()
    {
        return $this->remember_token; // Assuming you have a "remember_token" field
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
        $this->save(); // Save the updated model
    }

    public function getRememberTokenName()
    {
        return 'remember_token'; // Assuming the field name is "remember_token"
    }

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

    /**
     * Devuelve el correo del usuario
     *
     * @return string
     */
    public function getCoreo()
    {
        return $this->correo;
    }
}
