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
        Schema::create('indicador_agno', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('idCurso');
            $table->unsignedBigInteger('idIndicador');
            $table->unsignedBigInteger('idAñoAcademico');
            $table->string('valor');
            $table->timestamps();
            $table->foreign('idCurso')->references('id')->on('curso')->onDelete('cascade');
            $table->foreign('idIndicador')->references('id')->on('indicador')->onDelete('cascade');
            $table->foreign('idAñoAcademico')->references('id')->on('a_academico')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicador_agno');
    }
};
