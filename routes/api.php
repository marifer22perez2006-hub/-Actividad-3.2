<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\LineaController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas API REST para cada entidad de la base de datos
Route::apiResource('productos', ProductoController::class);
Route::apiResource('tipos', TipoController::class);
Route::apiResource('marcas', MarcaController::class);
Route::apiResource('lineas', LineaController::class);
Route::apiResource('insumos', InsumoController::class);
Route::apiResource('empleados', EmpleadoController::class);
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('pedidos', PedidoController::class);

//Productos
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::post('/productos/guardar', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/productos/actualizar', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/eliminar', [ProductoController::class, 'destroy'])->name('productos.destroy');

//Tipos
    Route::get('/tipos', [TipoController::class, 'index'])->name('tipos.index');
    Route::post('/tipos/guardar', [TipoController::class, 'store'])->name('tipos.store');
    Route::put('/tipos/actualizar', [TipoController::class, 'update'])->name('tipos.update');
    Route::delete('/tipos/eliminar', [TipoController::class, 'destroy'])->name('tipos.destroy');

//Marcas
    Route::get('/marcas', [MarcaController::class, 'index'])->name('marcas.index');
    Route::post('/marcas/guardar', [MarcaController::class, 'store'])->name('marcas.store');
    Route::put('/marcas/actualizar', [MarcaController::class, 'update'])->name('marcas.update');
    Route::delete('/marcas/eliminar', [MarcaController::class, 'destroy'])->name('marcas.destroy');

//Lineas
    Route::get('/lineas', [LineaController::class, 'index'])->name('lineas.index');
    Route::post('/lineas/guardar', [LineaController::class, 'store'])->name('lineas.store');
    Route::put('/lineas/actualizar', [LineaController::class, 'update'])->name('lineas.update');
    Route::delete('/lineas/eliminar', [LineaController::class, 'destroy'])->name('lineas.destroy');

//Insumos
    Route::get('/insumos', [InsumoController::class, 'index'])->name('insumos.index');
    Route::post('/insumos/guardar', [InsumoController::class, 'store'])->name('insumos.store');
    Route::put('/insumos/actualizar', [InsumoController::class, 'update'])->name('insumos.update');
    Route::delete('/insumos/eliminar', [InsumoController::class, 'destroy'])->name('insumos.destroy');  

//Empleados
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::post('/empleados/guardar', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::put('/empleados/actualizar', [EmpleadoController::class, 'update'])->name('empleados.update');
    Route::delete('/empleados/eliminar', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

//Clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::post('/clientes/guardar', [ClienteController::class, 'store'])->name('clientes.store');
    Route::put('/clientes/actualizar', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/eliminar', [ClienteController::class, 'destroy'])->name('clientes.destroy');

//Pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/pedidos/guardar', [PedidoController::class, 'store'])->name('pedidos.store'); 
    Route::put('/pedidos/actualizar', [PedidoController::class, 'update'])->name('pedidos.update');
    Route::delete('/pedidos/eliminar', [PedidoController::class, 'destroy'])->name('pedidos.destroy');


