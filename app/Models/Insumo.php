<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable = [
        'nombre',
        'unidad_medida', // kg, litros, gramos, piezas
        'cantidad_stock',
        'precio_unidad',
        'imagen',
        'estado'
    ];
}
