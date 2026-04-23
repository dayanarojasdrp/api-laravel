<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('estudiante_grupo', function (Blueprint $table) {

            $table->id();

            // 🔹 FK estudiante
            $table->foreignId('estudiante_id')
                  ->constrained('estudiantes')
                  ->cascadeOnDelete();

            // 🔹 FK grupo
            $table->foreignId('grupo_id')
                  ->constrained('grupos')
                  ->cascadeOnDelete();

            // 🔥 HISTORIAL
            $table->timestamp('fecha')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiante_grupo');
    }
};
