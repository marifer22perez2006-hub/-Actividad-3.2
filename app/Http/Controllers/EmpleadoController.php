<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        return response()->json($empleados, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|unique:empleados,correo',
            'telefono' => 'required|string',
            'cargo' => 'required|string',
            'salario' => 'required|numeric|min:0',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto' => 'nullable|string',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('foto_file');
        $empleado = Empleado::create($data);

        if ($request->hasFile('foto_file') && $request->file('foto_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('foto_file'),
                "empleado-{$empleado->id}-foto"
            );
            if ($apiUrl) {
                $empleado->update(['foto' => $apiUrl]);
            }
        }

        return response()->json([
            'message' => 'Empleado registrado correctamente.',
            'empleado' => $empleado->fresh()
        ], 201);
    }

    public function show($id)
    {
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        return response()->json($empleado, 200);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombres' => 'sometimes|required|string|max:255',
            'apellidos' => 'sometimes|required|string|max:255',
            'correo' => 'sometimes|required|email|unique:empleados,correo,' . $id,
            'telefono' => 'sometimes|required|string',
            'cargo' => 'sometimes|required|string',
            'salario' => 'sometimes|required|numeric|min:0',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto' => 'nullable|string',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('foto_file');

        if ($request->hasFile('foto_file') && $request->file('foto_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('foto_file'),
                "empleado-{$empleado->id}-foto"
            );
            if ($apiUrl) {
                $data['foto'] = $apiUrl;
            }
        }

        $empleado->update($data);

        return response()->json([
            'message' => 'Empleado actualizado correctamente.',
            'empleado' => $empleado->fresh()
        ], 200);
    }

    public function destroy($id)
    {
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }

        try {
            $empleado->delete();
            return response()->json(['message' => 'Empleado eliminado exitosamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'No se puede eliminar este empleado porque tiene registros o ventas asociadas.'], 409);
        }
    }
}
