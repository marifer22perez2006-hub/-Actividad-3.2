<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    protected $table = 'lineas';
    protected $fillable = ['nombre', 'imagen'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}