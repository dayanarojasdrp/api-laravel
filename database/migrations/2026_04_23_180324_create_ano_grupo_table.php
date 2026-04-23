<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('ano_grupo', function (Blueprint $table) {

            $table->id();

            // 🔹 FK a año académico (IMPORTANTE: a_academico)
            $table->foreignId('ano_academico_id')
                  ->constrained('a_academico')
                  ->cascadeOnDelete();

            // 🔹 FK a grupo
            $table->foreignId('grupo_id')
                  ->constrained('grupos')
                  ->cascadeOnDelete();

            // 🔥 HISTORIAL (recomendado)
            $table->timestamp('fecha')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ano_grupo');
    }
};
