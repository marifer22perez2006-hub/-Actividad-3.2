<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('administradores')) {
            Schema::create('administradores', function (Blueprint $table) {
                $table->id();
                $table->string('usuario')->unique();
                $table->string('contraseña');
                $table->string('nombre');
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};
