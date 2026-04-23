<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('decano', function (Blueprint $table) {

            // ❌ eliminar lo que ya no sirve
            $table->dropForeign(['id_curso']);
            $table->dropColumn('id_curso');

            // ✅ nuevos campos
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('habilitado')->default(true);

        });
    }

    public function down(): void
    {
        Schema::table('decano', function (Blueprint $table) {

            $table->unsignedBigInteger('id_curso')->nullable();

            $table->dropColumn([
                'fecha_inicio',
                'fecha_fin',
                'habilitado'
            ]);
        });
    }
};
