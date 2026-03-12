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
        Schema::create('indicador', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipoDeDato');
            $table->unsignedBigInteger('idTipoDeIndicador')->nullable();
            $table->string('asociado');
            $table->timestamps();
            $table->foreign('idTipoDeIndicador')->references('id')->on('tipo_indicador')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicador');
    }
};
