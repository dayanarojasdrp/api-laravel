<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jefe_de_departamento', function (Blueprint $table) {




            // ✅ nuevos campos de historial
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('habilitado')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('jefe_de_departamento', function (Blueprint $table) {


            // eliminar nuevos campos
            $table->dropColumn([
                'fecha_inicio',
                'fecha_fin',
                'habilitado'
            ]);
        });
    }
};
