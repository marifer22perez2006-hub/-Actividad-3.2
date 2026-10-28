<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalles_pedidos';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio',
        'descuento'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}