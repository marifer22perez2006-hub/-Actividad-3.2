<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['detalles.producto', 'cliente', 'empleado'])->orderBy('created_at', 'desc')->get();
        return response()->json($pedidos, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total' => 'required|numeric|min:0',
            'cliente_id' => 'nullable|exists:clientes,id',
            'empleado_id' => 'nullable|exists:empleados,id',
            'estado' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pedido = Pedido::create([
            'cliente_id' => $request->cliente_id ?? 1,
            'empleado_id' => $request->empleado_id ?? 1,
            'fecha' => now(),
            'iva' => $request->total * 0.16,
            'descuento' => $request->descuento ?? 0,
            'total' => $request->total,
            'estado' => $request->estado ?? 'Completado',
        ]);

        return response()->json([
            'message' => 'Pedido registrado exitosamente.',
            'pedido' => $pedido->load(['detalles.producto', 'cliente', 'empleado'])
        ], 201);
    }

    public function show($id)
    {
        $pedido = Pedido::with(['detalles.producto', 'cliente', 'empleado'])->find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json($pedido, 200);
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'total' => 'sometimes|required|numeric|min:0',
            'estado' => 'nullable|string',
            'cliente_id' => 'nullable|exists:clientes,id',
            'empleado_id' => 'nullable|exists:empleados,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pedido->update($request->all());

        return response()->json([
            'message' => 'Pedido actualizado exitosamente.',
            'pedido' => $pedido->fresh()->load(['detalles.producto', 'cliente', 'empleado'])
        ], 200);
    }

    public function destroy($id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $pedido->delete();
        return response()->json(['message' => 'Pedido eliminado exitosamente.'], 200);
    }
}
