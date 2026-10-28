<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $productos = Producto::where('estado', 1)->where('existencia', '>', 0)->get();
        return view('pos.index', compact('productos'));
    }

    public function create()
    {
        return view('pos.create');
    }

    public function edit(Request $request)
    {
        return view('pos.edit');
    }

    public function show(Request $request)
    {
        return view('pos.show');
    }

    public function procesarVenta(Request $request)
    {
        $request->validate([
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'total' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id ?? 1,
                'empleado_id' => $request->empleado_id ?? 1,
                'fecha' => now(),
                'iva' => $request->total * 0.16,
                'descuento' => 0,
                'total' => $request->total,
                'estado' => 'Completado',
            ]);

            foreach ($request->productos as $item) {
                $producto = Producto::find($item['id']);

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio' => $producto->precio,
                    'descuento' => 0,
                ]);

                // Descontar existencia del inventario
                $producto->decrement('existencia', $item['cantidad']);
            }
        });

        return response()->json(['success' => true, 'message' => 'Venta procesada con éxito']);
    }
}
