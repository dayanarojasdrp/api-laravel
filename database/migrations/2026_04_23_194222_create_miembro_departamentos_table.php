<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('miembro_departamento', function (Blueprint $table) {

            $table->id();

            // 🔹 FK profesor
            $table->foreignId('id_profesor')
                  ->constrained('profesor')
                  ->cascadeOnDelete();

            // 🔹 FK departamento
            $table->foreignId('id_departamento')
                  ->constrained('departamento')
                  ->cascadeOnDelete();

            // 🔥 historial
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('habilitado')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('miembro_departamento');
    }
};
