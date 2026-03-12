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
        Schema::create('asignatura_agno', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('id_asignatura');
            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_a_academico');
            $table->timestamps();
            $table->foreign('id_asignatura')->references('id')->on('asignatura')->onDelete('cascade');
            $table->foreign('id_a_academico')->references('id')->on('a_academico')->onDelete('cascade');
            $table->foreign('id_curso')->references('id')->on('curso')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignatura_agno');
    }
};
