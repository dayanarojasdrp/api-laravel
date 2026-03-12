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
            $table->unsignedBigInteger('id_curso');
            $table->timestamps();
            $table->primary('uuid');
            $table->foreign('id_profesor')->references('id')->on('profesor')->onDelete('cascade');
            $table->foreign('id_departamento')->references('id')->on('departamento')->onDelete('cascade');
            $table->foreign('id_curso')->references('id')->on('curso')->onDelete('cascade');
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
