<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('administradores')) {
            Schema::table('administradores', function (Blueprint $table) {
                if (!Schema::hasColumn('administradores', 'google_id')) {
                    $table->string('google_id')->nullable()->after('nombre');
                }
                if (!Schema::hasColumn('administradores', 'correo')) {
                    $table->string('correo')->nullable()->after('google_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('administradores')) {
            Schema::table('administradores', function (Blueprint $table) {
                if (Schema::hasColumn('administradores', 'google_id')) {
                    $table->dropColumn('google_id');
                }
                if (Schema::hasColumn('administradores', 'correo')) {
                    $table->dropColumn('correo');
                }
            });
        }
    }
};
