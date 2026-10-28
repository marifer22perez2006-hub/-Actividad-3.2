<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Tipo;
use App\Models\Marca;
use App\Models\Linea;
use App\Models\Producto;
use App\Models\Insumo;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador por defecto para autenticación manual
        Administrador::firstOrCreate(['usuario' => 'admin'], [
            'contraseña' => Hash::make('admin123'),
            'nombre' => 'Administrador B&G',
            'activo' => 1,
        ]);

        // Cliente por defecto
        Cliente::firstOrCreate(['id' => 1], [
            'nombre' => 'Cliente General (Venta en Mostrador)',
            'correo' => 'cliente@reposteria.com',
            'telefono' => '555-000-0000',
            'direccion' => 'Mostrador Principal',
        ]);

        // Empleado por defecto
        Empleado::firstOrCreate(['id' => 1], [
            'nombres' => 'Cajero',
            'apellidos' => 'Principal',
            'correo' => 'cajero@reposteria.com',
            'telefono' => '555-111-2222',
            'cargo' => 'Cajero',
            'salario' => 8000.00,
        ]);

        // Tipos de Postre
        $tipoPasteles = Tipo::firstOrCreate(['nombre' => 'Pasteles Tradicionales'], [
            'imagen' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500'
        ]);
        $tipoGalletas = Tipo::firstOrCreate(['nombre' => 'Galletas Artesanales'], [
            'imagen' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500'
        ]);

        // Marcas
        $marcaCasera = Marca::firstOrCreate(['nombre' => 'B&G Artesanal'], [
            'imagen' => 'https://images.unsplash.com/photo-1535141192574-5d4897c13136?w=500'
        ]);

        // Líneas
        $lineaGourmet = Linea::firstOrCreate(['nombre' => 'Línea Gourmet'], [
            'imagen' => 'https://images.unsplash.com/photo-1557089706-6fd900483863?w=500'
        ]);

        // Insumos
        Insumo::firstOrCreate(['nombre' => 'Harina de Trigo Extra Fina'], [
            'unidad_medida' => 'kg',
            'cantidad_stock' => 50.00,
            'precio_unidad' => 22.50,
        ]);
        Insumo::firstOrCreate(['nombre' => 'Azúcar Refinada'], [
            'unidad_medida' => 'kg',
            'cantidad_stock' => 40.00,
            'precio_unidad' => 28.00,
        ]);
        Insumo::firstOrCreate(['nombre' => 'Mantequilla sin Sal'], [
            'unidad_medida' => 'kg',
            'cantidad_stock' => 20.00,
            'precio_unidad' => 95.00,
        ]);

        // Productos
        Producto::firstOrCreate(['nombre' => 'Pastel Tres Leches con Fresas'], [
            'tipo_id' => $tipoPasteles->id,
            'marca_id' => $marcaCasera->id,
            'linea_id' => $lineaGourmet->id,
            'descripcion' => 'Delicioso pastel de tres leches decorado con fresas naturales y crema batida.',
            'precio' => 320.00,
            'existencia' => 10,
            'descuento' => 0.00,
            'imagen1' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500',
            'estado' => 1
        ]);

        Producto::firstOrCreate(['nombre' => 'Galletas de Chispas de Chocolate (Pack 6)'], [
            'tipo_id' => $tipoGalletas->id,
            'marca_id' => $marcaCasera->id,
            'linea_id' => $lineaGourmet->id,
            'descripcion' => 'Pack de 6 galletas horneadas diariamente con Choco-chips Hershey\'s.',
            'precio' => 85.00,
            'existencia' => 25,
            'descuento' => 0.00,
            'imagen1' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500',
            'estado' => 1
        ]);
    }
}
