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
        Schema::create('a_academico_curso', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('id_a_academico');
            $table->unsignedBigInteger('id_curso');
            $table->uuid('id_cohorte');
            $table->timestamps();
            $table->foreign('id_a_academico')->references('id')->on('a_academico')->onDelete('cascade');
            $table->foreign('id_curso')->references('id')->on('curso')->onDelete('cascade');
            $table->foreign('id_cohorte')->references('id')->on('cohorte')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a-academico_curso');
    }
};
