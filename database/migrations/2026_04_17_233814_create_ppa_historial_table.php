<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('ppa_historial', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('id_profesor');
        $table->unsignedBigInteger('id_a_academico');
        $table->unsignedBigInteger('id_curso'); // 👈 FALTA ESTO

        $table->enum('accion', ['designado', 'ratificado', 'desnombrado']);

        $table->timestamp('fecha_accion');

        $table->timestamps();

        // 🔗 RELACIONES (MUY IMPORTANTE)
        $table->foreign('id_profesor')->references('id')->on('profesor')->onDelete('cascade');
        $table->foreign('id_a_academico')->references('id')->on('a_academico')->onDelete('cascade');
        $table->foreign('id_curso')->references('id')->on('curso')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppa_historial');
    }
};
