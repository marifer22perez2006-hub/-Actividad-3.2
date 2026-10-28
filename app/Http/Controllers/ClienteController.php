<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('foto_file');
        $cliente = Cliente::create($data);

        if ($request->hasFile('foto_file') && $request->file('foto_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('foto_file'),
                "cliente-{$cliente->id}-foto"
            );
            if ($apiUrl) {
                $cliente->update(['foto' => $apiUrl]);
            }
        }

        return response()->json([
            'message' => 'Cliente registrado correctamente.',
            'cliente' => $cliente->fresh()
        ], 201);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente, 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('foto_file');

        if ($request->hasFile('foto_file') && $request->file('foto_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('foto_file'),
                "cliente-{$cliente->id}-foto"
            );
            if ($apiUrl) {
                $data['foto'] = $apiUrl;
            }
        }

        $cliente->update($data);

        return response()->json([
            'message' => 'Cliente actualizado correctamente.',
            'cliente' => $cliente->fresh()
        ], 200);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        try {
            $cliente->delete();
            return response()->json(['message' => 'Cliente eliminado exitosamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'No se puede eliminar este cliente porque tiene ventas o pedidos asociados.'], 409);
        }
    }
}
