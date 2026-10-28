<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'nombres',
        'apellidos',
        'correo',
        'telefono',
        'cargo', // Repostero, Cajero, Administrador
        'salario',
        'foto',
        'estado'
    ];
}
