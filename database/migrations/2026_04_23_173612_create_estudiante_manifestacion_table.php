<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estudiante_manifestacion', function (Blueprint $table) {

            $table->id();

            $table->foreignId('estudiante_id')
                  ->constrained('estudiantes')
                  ->cascadeOnDelete();

            $table->foreignId('manifestacion_id')
                  ->constrained('manifestaciones')
                  ->cascadeOnDelete();

            // 🔥 CLAVE PARA HISTORIAL
            $table->timestamp('fecha')->nullable();

            // 🔥 CONTROL EXTRA
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiante_manifestacion');
    }
};
