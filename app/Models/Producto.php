<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'tipo_id',
        'marca_id',
        'linea_id',
        'nombre',
        'descripcion',
        'precio',
        'existencia',
        'descuento',
        'imagen1',
        'imagen2',
        'imagen3',
        'estado',
    ];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function linea()
    {
        return $this->belongsTo(Linea::class);
    }
}