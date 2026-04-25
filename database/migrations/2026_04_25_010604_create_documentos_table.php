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
       Schema::create('documentos', function (Blueprint $table) {
    $table->id();
    $table->string('nombre'); // nombre bonito
    $table->string('tipo'); // ppa | aa
    $table->string('tipo_documento'); // listado | resolucion
    $table->string('periodo')->nullable(); // 2025-2026
    $table->string('ruta'); // donde está guardado
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
