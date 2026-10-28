<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('empleados') && !Schema::hasColumn('empleados', 'foto')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('salario');
            });
        }

        if (Schema::hasTable('insumos') && !Schema::hasColumn('insumos', 'imagen')) {
            Schema::table('insumos', function (Blueprint $table) {
                $table->string('imagen')->nullable()->after('precio_unidad');
            });
        }

        if (Schema::hasTable('clientes') && !Schema::hasColumn('clientes', 'foto')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('direccion');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('empleados') && Schema::hasColumn('empleados', 'foto')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->dropColumn('foto');
            });
        }

        if (Schema::hasTable('insumos') && Schema::hasColumn('insumos', 'imagen')) {
            Schema::table('insumos', function (Blueprint $table) {
                $table->dropColumn('imagen');
            });
        }

        if (Schema::hasTable('clientes') && Schema::hasColumn('clientes', 'foto')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->dropColumn('foto');
            });
        }
    }
};
