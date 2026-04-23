<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesor_guia', function (Blueprint $table) {

            $table->id();

            // 🔹 profesor
            $table->unsignedBigInteger('id_profesor');

            // 🔹 grupo (OJO → grupos)
            $table->unsignedBigInteger('id_grupo');

            // 🔥 historial
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('habilitado')->default(true);

            $table->timestamps();

            // 🔗 FKs CORRECTAS
            $table->foreign('id_profesor')
                  ->references('id')
                  ->on('profesor')
                  ->onDelete('cascade');

            $table->foreign('id_grupo')
                  ->references('id')
                  ->on('grupos') // 👈 AQUÍ ESTÁ EL FIX
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesor_guia');
    }
};
