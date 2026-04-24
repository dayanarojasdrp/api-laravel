<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumno_ayudante', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_estudiante')
                  ->constrained('estudiantes')
                  ->cascadeOnDelete();

            $table->string('nombre_tutor');
            $table->string('etapa');

            // 🔥 historial
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('habilitado')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumno_ayudante');
    }
};
