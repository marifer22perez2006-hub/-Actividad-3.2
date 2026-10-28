<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::all();
        return response()->json($marcas, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:marcas,nombre|max:255',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('imagen_file');

        if (empty($data['imagen']) && !$request->hasFile('imagen_file')) {
            $data['imagen'] = 'https://images.unsplash.com/photo-1535141192574-5d4897c13136?w=500';
        }

        $marca = Marca::create($data);

        if ($request->hasFile('imagen_file') && $request->file('imagen_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen_file'),
                "marca-{$marca->id}-imagen"
            );
            if ($apiUrl) {
                $marca->update(['imagen' => $apiUrl]);
            }
        }

        return response()->json([
            'message' => 'Marca creada correctamente.',
            'marca' => $marca->fresh()
        ], 201);
    }

    public function show($id)
    {
        $marca = Marca::find($id);
        if (!$marca) {
            return response()->json(['message' => 'Marca no encontrada'], 404);
        }

        return response()->json($marca, 200);
    }

    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);
        if (!$marca) {
            return response()->json(['message' => 'Marca no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|max:255|unique:marcas,nombre,' . $id,
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
                "marca-{$marca->id}-imagen"
            );
            if ($apiUrl) {
                $data['imagen'] = $apiUrl;
            }
        }

        $marca->update($data);

        return response()->json([
            'message' => 'Marca actualizada correctamente.',
            'marca' => $marca->fresh()
        ], 200);
    }

    public function destroy($id)
    {
        $marca = Marca::find($id);
        if (!$marca) {
            return response()->json(['message' => 'Marca no encontrada'], 404);
        }

        try {
            $marca->delete();
            return response()->json(['message' => 'Marca eliminada exitosamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'No se puede eliminar esta marca porque tiene productos asociados.'], 409);
        }
    }
}
