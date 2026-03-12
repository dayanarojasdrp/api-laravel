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
        Schema::create('disciplina_asignatura', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('id_disciplina');
            $table->unsignedBigInteger('id_asignatura');
            $table->unsignedBigInteger('id_curso');
            $table->timestamps();
            $table->foreign('id_curso')->references('id')->on('curso')->onDelete('cascade');
            $table->foreign('id_disciplina')->references('id')->on('disciplina')->onDelete('cascade');
            $table->foreign('id_asignatura')->references('id')->on('asignatura')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplina_asignatura');
    }
};
