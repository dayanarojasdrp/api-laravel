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
        Schema::create('profesor', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->unsignedBigInteger('idCatDocente');
            $table->unsignedBigInteger('idCatCientifica');
            $table->timestamps();
            $table->foreign('idCatDocente')->references('id')->on('categoria_docente')->onDelete('cascade');
            $table->foreign('idCatCientifica')->references('id')->on('categoria_cientifica')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesor');
    }
};
