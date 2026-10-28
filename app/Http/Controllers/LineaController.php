<?php

namespace App\Http\Controllers;

use App\Models\Linea;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LineaController extends Controller
{
    public function index()
    {
        $lineas = Linea::all();
        return response()->json($lineas, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:lineas,nombre|max:255',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('imagen_file');

        if (empty($data['imagen']) && !$request->hasFile('imagen_file')) {
            $data['imagen'] = 'https://images.unsplash.com/photo-1557089706-6fd900483863?w=500';
        }

        $linea = Linea::create($data);

        if ($request->hasFile('imagen_file') && $request->file('imagen_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen_file'),
                "linea-{$linea->id}-imagen"
            );
            if ($apiUrl) {
                $linea->update(['imagen' => $apiUrl]);
            }
        }

        return response()->json([
            'message' => 'Línea creada correctamente.',
            'linea' => $linea->fresh()
        ], 201);
    }

    public function show($id)
    {
        $linea = Linea::find($id);
        if (!$linea) {
            return response()->json(['message' => 'Línea no encontrada'], 404);
        }

        return response()->json($linea, 200);
    }

    public function update(Request $request, $id)
    {
        $linea = Linea::find($id);
        if (!$linea) {
            return response()->json(['message' => 'Línea no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|max:255|unique:lineas,nombre,' . $id,
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('imagen_file');

        if ($request->hasFile('imagen_file') && $request->file('imagen_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen_file'),
                "linea-{$linea->id}-imagen"
            );
            if ($apiUrl) {
                $data['imagen'] = $apiUrl;
            }
        }

        $linea->update($data);

        return response()->json([
            'message' => 'Línea actualizada correctamente.',
            'linea' => $linea->fresh()
        ], 200);
    }

    public function destroy($id)
    {
        $linea = Linea::find($id);
        if (!$linea) {
            return response()->json(['message' => 'Línea no encontrada'], 404);
        }

        try {
            $linea->delete();
            return response()->json(['message' => 'Línea eliminada exitosamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'No se puede eliminar esta línea porque tiene productos asociados.'], 409);
        }
    }
}
