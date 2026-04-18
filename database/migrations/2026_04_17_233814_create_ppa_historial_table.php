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

    $table->enum('accion', ['designado', 'ratificado', 'desnombrado']);

    $table->timestamp('fecha_accion');

    $table->timestamps();
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
