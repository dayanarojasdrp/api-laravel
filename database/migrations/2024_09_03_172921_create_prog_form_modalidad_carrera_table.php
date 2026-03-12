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
        Schema::create('prog_form_modalidad_carrera', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->primary('uuid');
            $table->unsignedBigInteger('id_modalidad');
            $table->unsignedBigInteger('id_prog_form');
            $table->timestamps();
            $table->foreign('id_modalidad')->references('id')->on('modalidad_carrera')->onDelete('no action');
            $table->foreign('id_prog_form')->references('id')->on('programa_de_formacion')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prog-form-modalidad-carrera');
    }
};
