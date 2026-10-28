<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Tipo;
use App\Models\Marca;
use App\Models\Linea;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['tipo', 'marca', 'linea'])->get();
        //return view('productos.index', compact('productos'));
        //esto en todos los casos
        return response()->json($productos);
    }

    // public function create()
    // {
    //     $tipos = Tipo::all();
    //     $marcas = Marca::all();
    //     $lineas = Linea::all();
    //     return view('productos.create', compact('tipos', 'marcas', 'lineas'));
    // }


    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
    'tipo_id' => 'required|exists:tipos,id',
    'marca_id' => 'required|exists:marcas,id',
    'linea_id' => 'required|exists:lineas,id',
    'nombre' => 'required|string|max:255',
    'descripcion' => 'required|string',
    'precio' => 'required|numeric|min:0',
    'existencia' => 'required|integer|min:0',
    'descuento' => 'nullable|numeric|min:0',
    'imagen1_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
   // 'imagen1' => 'nullable|string',
    'imagen2_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    'imagen2' => 'nullable|string',
    'imagen3_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    'imagen3' => 'nullable|string',
]);

// Para verificar si falla y manejar los errores manualmente:
if ($validator->fails()) {
    // Si estás en una petición AJAX / API:
    return response()->json([
        'errors' => $validator->errors()
    ], 422);

    // O si es una petición web normal (redirección con errores):
    // return redirect()->back()->withErrors($validator)->withInput();
}

// Si la validación pasa, puedes obtener los datos validados así:
$validatedData = $validator->validated();

        $data = $request->except(['imagen1_file', 'imagen2_file', 'imagen3_file']);
        if (empty($data['imagen1']) && !$request->hasFile('imagen1_file')) {
            $data['imagen1'] = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500';
        }

        $producto = Producto::create($data);
        $uploadedImages = [];

        foreach (['imagen1_file' => 'imagen1', 'imagen2_file' => 'imagen2', 'imagen3_file' => 'imagen3'] as $fileField => $imageField) {
            if ($request->hasFile($fileField) && $request->file($fileField)->isValid()) {
                $apiUrl = ImageUploadService::upload(
                    $request->file($fileField),
                    "producto-{$producto->id}-{$imageField}"
                );

                if (!$apiUrl) {
                    $producto->delete();
                    return response()->json([
                        'message' => 'No se pudo subir una imagen a FreeImage.host.',
                        'field' => $fileField,
                    ], 502);
                }

                $uploadedImages[$imageField] = $apiUrl;
            }
        }

        if ($uploadedImages) {
            $producto->update($uploadedImages);
        }

       // return redirect()->route('productos.index')->with('success', 'Producto registrado exitosamente.');
        return response()->json(['message' => 'Producto registrado exitosamente.'], 201);
    }

    public function show($id)
    {
       // $request->validate([
       //     'id' => 'required|integer|exists:productos,id',
       // ]);

      $producto = Producto::with(['tipo', 'marca', 'linea'])->find($id);

if (!$producto) {
    return response()->json([
        'message' => 'Producto no encontrado'
    ], 404);
}

return response()->json($producto);
    }

    // public function edit(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|integer|exists:productos,id',
    //     ]);

    //     $producto = Producto::findOrFail($request->input('id'));
    //     $tipos = Tipo::all();
    //     $marcas = Marca::all();
    //     $lineas = Linea::all();
    //     return view('productos.edit', compact('producto', 'tipos', 'marcas', 'lineas'));
    // }

    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo_id' => 'sometimes|required|exists:tipos,id',
            'marca_id' => 'sometimes|required|exists:marcas,id',
            'linea_id' => 'sometimes|required|exists:lineas,id',
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'existencia' => 'sometimes|required|integer|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'imagen1_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen1' => 'nullable|string',
            'imagen2_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen2' => 'nullable|string',
            'imagen3_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen3' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['imagen1_file', 'imagen2_file', 'imagen3_file']);

        if ($request->hasFile('imagen1_file') && $request->file('imagen1_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen1_file'),
                "producto-{$producto->id}-imagen1"
            );
            if ($apiUrl) {
                $data['imagen1'] = $apiUrl;
            }
        }

        if ($request->hasFile('imagen2_file') && $request->file('imagen2_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen2_file'),
                "producto-{$producto->id}-imagen2"
            );
            if ($apiUrl) {
                $data['imagen2'] = $apiUrl;
            }
        }

        if ($request->hasFile('imagen3_file') && $request->file('imagen3_file')->isValid()) {
            $apiUrl = ImageUploadService::upload(
                $request->file('imagen3_file'),
                "producto-{$producto->id}-imagen3"
            );
            if ($apiUrl) {
                $data['imagen3'] = $apiUrl;
            }
        }

        $producto->update($data);

        return response()->json([
            'message' => 'Producto actualizado exitosamente.',
            'producto' => $producto->fresh()
        ], 200);
    }

    public function destroy($id)
    {
        // $request->validate([
        //     'id' => 'required|integer|exists:productos,id',
        // ]);


            $producto = Producto::find($id);
            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }
            $producto->delete();
            return response()->json(['message' => 'Producto eliminado exitosamente.'], 200);

    }
}
