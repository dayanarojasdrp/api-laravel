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
        Schema::create('a_academico', function (Blueprint $table) {
            $table->id();
            $table->string('identificador');
            $table->unsignedBigInteger('id_prog_form')->nullable();
            $table->timestamps();
            $table->foreign('id_prog_form')->references('id')->on('programa_de_formacion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a-academico');
    }
};
