<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'fecha',
        'iva',
        'descuento',
        'total',
        'estado'
    ];

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
