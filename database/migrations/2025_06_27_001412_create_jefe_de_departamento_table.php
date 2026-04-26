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
        Schema::create('jefe_de_departamento', function (Blueprint $table) {
    $table->uuid('uuid');
    $table->unsignedBigInteger('id_departamento');
    $table->unsignedBigInteger('id_profesor');
    $table->timestamps();

    $table->primary('uuid');

    $table->foreign('id_profesor')
        ->references('id')->on('profesor')
        ->onDelete('cascade');

    $table->foreign('id_departamento')
        ->references('id')->on('departamento')
        ->onDelete('cascade');

    // 🔥 REGLAS CLAVE
    $table->unique('id_departamento'); // 1 jefe por departamento
    $table->unique('id_profesor');     // 1 profesor solo puede ser jefe una vez
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jefe_de_departamento');
    }
};
