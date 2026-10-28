<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoController extends Controller
{
    public function index()
    {
        $tipos = Tipo::all();
        return response()->json($tipos, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:tipos,nombre|max:255',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('imagen_file');

        if (empty($data['imagen']) && !$request->hasFile('imagen_file')) {
            $data['imagen'] = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500';
        }

        $tipo = Tipo::create($data);

        if ($request->hasFile('imagen_file') && $request->file('imagen_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen_file'),
                "tipo-{$tipo->id}-imagen"
            );
            if ($apiUrl) {
                $tipo->update(['imagen' => $apiUrl]);
            }
        }

        return response()->json([
            'message' => 'Tipo creado correctamente.',
            'tipo' => $tipo->fresh()
        ], 201);
    }

    public function show($id)
    {
        $tipo = Tipo::find($id);
        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        return response()->json($tipo, 200);
    }

    public function update(Request $request, $id)
    {
        $tipo = Tipo::find($id);
        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|max:255|unique:tipos,nombre,' . $id,
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
                "tipo-{$tipo->id}-imagen"
            );
            if ($apiUrl) {
                $data['imagen'] = $apiUrl;
            }
        }

        $tipo->update($data);

        return response()->json([
            'message' => 'Tipo actualizado correctamente.',
            'tipo' => $tipo->fresh()
        ], 200);
    }

    public function destroy($id)
    {
        $tipo = Tipo::find($id);
        if (!$tipo) {
            return response()->json(['message' => 'Tipo no encontrado'], 404);
        }

        try {
            $tipo->delete();
            return response()->json(['message' => 'Tipo eliminado exitosamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'No se puede eliminar este tipo porque tiene productos asociados.'], 409);
        }
    }
}
