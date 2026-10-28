<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\LineaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\GeolocalizacionController;
use App\Http\Controllers\ClimaController;
use App\Http\Controllers\DivisaController;

// ==========================================
// RUTAS PÚBLICAS (Autenticación Manual y Google API)
// ==========================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Autenticación con Google (Socialite OAuth)
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/google/demo', [SocialAuthController::class, 'demoGoogleLogin'])->name('auth.google.demo');

// ==========================================
// RUTAS PROTEGIDAS CON MIDDLEWARE (auth:admin)
// ==========================================
Route::middleware(['auth:admin'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect('/pos');
    });

    // Punto de Venta (POS)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/crear', [PosController::class, 'create'])->name('pos.create');
    Route::get('/pos/editar', [PosController::class, 'edit'])->name('pos.edit');
    Route::get('/pos/mostrar', [PosController::class, 'show'])->name('pos.show');
    Route::post('/pos/procesar', [PosController::class, 'procesarVenta'])->name('pos.procesar');

    // // Tipos de Postre
    // Route::get('/tipos', [TipoController::class, 'index'])->name('tipos.index');
    // Route::get('/tipos/crear', [TipoController::class, 'create'])->name('tipos.create');
    // Route::post('/tipos/guardar', [TipoController::class, 'store'])->name('tipos.store');
    // Route::get('/tipos/mostrar', [TipoController::class, 'show'])->name('tipos.show');
    // Route::get('/tipos/editar', [TipoController::class, 'edit'])->name('tipos.edit');
    // Route::post('/tipos/actualizar', [TipoController::class, 'update'])->name('tipos.update');
    // Route::post('/tipos/eliminar', [TipoController::class, 'destroy'])->name('tipos.destroy');

    // // Marcas
    // Route::get('/marcas', [MarcaController::class, 'index'])->name('marcas.index');
    // Route::get('/marcas/crear', [MarcaController::class, 'create'])->name('marcas.create');
    // Route::post('/marcas/guardar', [MarcaController::class, 'store'])->name('marcas.store');
    // Route::get('/marcas/mostrar', [MarcaController::class, 'show'])->name('marcas.show');
    // Route::get('/marcas/editar', [MarcaController::class, 'edit'])->name('marcas.edit');
    // Route::post('/marcas/actualizar', [MarcaController::class, 'update'])->name('marcas.update');
    // Route::post('/marcas/eliminar', [MarcaController::class, 'destroy'])->name('marcas.destroy');

    // // Líneas
    // Route::get('/lineas', [LineaController::class, 'index'])->name('lineas.index');
    // Route::get('/lineas/crear', [LineaController::class, 'create'])->name('lineas.create');
    // Route::post('/lineas/guardar', [LineaController::class, 'store'])->name('lineas.store');
    // Route::get('/lineas/mostrar', [LineaController::class, 'show'])->name('lineas.show');
    // Route::get('/lineas/editar', [LineaController::class, 'edit'])->name('lineas.edit');
    // Route::post('/lineas/actualizar', [LineaController::class, 'update'])->name('lineas.update');
    // Route::post('/lineas/eliminar', [LineaController::class, 'destroy'])->name('lineas.destroy');

    // // Productos
    // Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    // Route::get('/productos/crear', [ProductoController::class, 'create'])->name('productos.create');
    // Route::post('/productos/guardar', [ProductoController::class, 'store'])->name('productos.store');
    // Route::get('/productos/mostrar', [ProductoController::class, 'show'])->name('productos.show');
    // Route::get('/productos/editar', [ProductoController::class, 'edit'])->name('productos.edit');
    // Route::post('/productos/actualizar', [ProductoController::class, 'update'])->name('productos.update');
    // Route::post('/productos/eliminar', [ProductoController::class, 'destroy'])->name('productos.destroy');

    // // Insumos
    // Route::get('/insumos', [InsumoController::class, 'index'])->name('insumos.index');
    // Route::get('/insumos/crear', [InsumoController::class, 'create'])->name('insumos.create');
    // Route::post('/insumos/guardar', [InsumoController::class, 'store'])->name('insumos.store');
    // Route::get('/insumos/mostrar', [InsumoController::class, 'show'])->name('insumos.show');
    // Route::get('/insumos/editar', [InsumoController::class, 'edit'])->name('insumos.edit');
    // Route::post('/insumos/actualizar', [InsumoController::class, 'update'])->name('insumos.update');
    // Route::post('/insumos/eliminar', [InsumoController::class, 'destroy'])->name('insumos.destroy');

    // // Empleados
    // Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    // Route::get('/empleados/crear', [EmpleadoController::class, 'create'])->name('empleados.create');
    // Route::post('/empleados/guardar', [EmpleadoController::class, 'store'])->name('empleados.store');
    // Route::get('/empleados/mostrar', [EmpleadoController::class, 'show'])->name('empleados.show');
    // Route::get('/empleados/editar', [EmpleadoController::class, 'edit'])->name('empleados.edit');
    // Route::post('/empleados/actualizar', [EmpleadoController::class, 'update'])->name('empleados.update');
    // Route::post('/empleados/eliminar', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

    // // Clientes
    // Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    // Route::get('/clientes/crear', [ClienteController::class, 'create'])->name('clientes.create');
    // Route::post('/clientes/guardar', [ClienteController::class, 'store'])->name('clientes.store');
    // Route::get('/clientes/mostrar', [ClienteController::class, 'show'])->name('clientes.show');
    // Route::get('/clientes/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
    // Route::post('/clientes/actualizar', [ClienteController::class, 'update'])->name('clientes.update');
    // Route::post('/clientes/eliminar', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    // // Pedidos
    // Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    // Route::get('/pedidos/crear', [PedidoController::class, 'create'])->name('pedidos.create');
    // Route::post('/pedidos/guardar', [PedidoController::class, 'store'])->name('pedidos.store');
    // Route::get('/pedidos/mostrar', [PedidoController::class, 'show'])->name('pedidos.show');
    // Route::get('/pedidos/editar', [PedidoController::class, 'edit'])->name('pedidos.edit');
    // Route::post('/pedidos/estado', [PedidoController::class, 'updateEstado'])->name('pedidos.updateEstado');
    // Route::post('/pedidos/actualizar', [PedidoController::class, 'update'])->name('pedidos.update');
    // Route::post('/pedidos/eliminar', [PedidoController::class, 'destroy'])->name('pedidos.destroy');

    // APIs de Geolocalización, Clima y Divisa
    Route::get('/api/geolocalizacion', [GeolocalizacionController::class, 'getUbicacion'])->name('api.geolocalizacion');
    Route::get('/api/clima', [ClimaController::class, 'getClima'])->name('api.clima');
    Route::get('/api/divisa', [DivisaController::class, 'getTasaDolar'])->name('api.divisa');
});
