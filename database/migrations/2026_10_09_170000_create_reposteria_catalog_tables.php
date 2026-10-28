<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('imagen')->nullable();
            $table->timestamps();
        });

        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('imagen')->nullable();
            $table->timestamps();
        });

        Schema::create('lineas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('imagen')->nullable();
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')->constrained('tipos')->restrictOnDelete();
            $table->foreignId('marca_id')->constrained('marcas')->restrictOnDelete();
            $table->foreignId('linea_id')->constrained('lineas')->restrictOnDelete();
            $table->string('nombre');
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->unsignedInteger('existencia')->default(0);
            $table->decimal('descuento', 5, 2)->default(0);
            $table->string('imagen1')->nullable();
            $table->string('imagen2')->nullable();
            $table->string('imagen3')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('unidad_medida', 50);
            $table->decimal('cantidad_stock', 10, 2)->default(0);
            $table->decimal('precio_unidad', 10, 2)->default(0);
            $table->string('imagen')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('correo')->unique();
            $table->string('telefono');
            $table->string('cargo');
            $table->decimal('salario', 10, 2)->default(0);
            $table->string('foto')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('empleado_id')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->decimal('iva', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('Completado');
            $table->timestamps();
        });

        Schema::create('detalles_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_pedidos');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('empleados');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('insumos');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('lineas');
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('tipos');
    }
};
