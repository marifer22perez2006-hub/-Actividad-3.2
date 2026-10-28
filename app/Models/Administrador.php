<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use Notifiable;

    protected $table = 'administradores';

    protected $fillable = [
        'usuario',
        'contraseña',
        'nombre',
        'correo',
        'google_id',
        'activo',
    ];

    protected $hidden = [
        'contraseña',
    ];

    /**
     * IMPORTANTE: Laravel busca el campo 'password'.
     * Como usamos 'contraseña', debemos indicárselo explícitamente.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }
}
