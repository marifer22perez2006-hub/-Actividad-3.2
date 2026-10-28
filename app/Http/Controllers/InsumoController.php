<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::all();
        return response()->json($insumos, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'unidad_medida' => 'required|string|max:50',
            'cantidad_stock' => 'required|numeric|min:0',
            'precio_unidad' => 'required|numeric|min:0',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen' => 'nullable|string',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('imagen_file');
        $insumo = Insumo::create($data);

        if ($request->hasFile('imagen_file') && $request->file('imagen_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen_file'),
                "insumo-{$insumo->id}-imagen"
            );
            if ($apiUrl) {
                $insumo->update(['imagen' => $apiUrl]);
            }
        }

        return response()->json([
            'message' => 'Insumo registrado correctamente.',
            'insumo' => $insumo->fresh()
        ], 201);
    }

    public function show($id)
    {
        $insumo = Insumo::find($id);
        if (!$insumo) {
            return response()->json(['message' => 'Insumo no encontrado'], 404);
        }

        return response()->json($insumo, 200);
    }

    public function update(Request $request, $id)
    {
        $insumo = Insumo::find($id);
        if (!$insumo) {
            return response()->json(['message' => 'Insumo no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'unidad_medida' => 'sometimes|required|string|max:50',
            'cantidad_stock' => 'sometimes|required|numeric|min:0',
            'precio_unidad' => 'sometimes|required|numeric|min:0',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen' => 'nullable|string',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('imagen_file');

        if ($request->hasFile('imagen_file') && $request->file('imagen_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen_file'),
                "insumo-{$insumo->id}-imagen"
            );
            if ($apiUrl) {
                $data['imagen'] = $apiUrl;
            }
        }

        $insumo->update($data);

        return response()->json([
            'message' => 'Insumo actualizado correctamente.',
            'insumo' => $insumo->fresh()
        ], 200);
    }

    public function destroy($id)
    {
        $insumo = Insumo::find($id);
        if (!$insumo) {
            return response()->json(['message' => 'Insumo no encontrado'], 404);
        }

        try {
            $insumo->delete();
            return response()->json(['message' => 'Insumo eliminado exitosamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'No se puede eliminar este insumo porque tiene registros asociados.'], 409);
        }
    }
}
