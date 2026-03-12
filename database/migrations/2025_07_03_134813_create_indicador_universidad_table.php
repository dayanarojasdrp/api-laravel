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
        Schema::create('indicador_universidad', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('idUniversidad');
            $table->unsignedBigInteger('idIndicador');
            $table->unsignedBigInteger('idCurso');
            $table->string('valor');
            $table->timestamps();
            $table->foreign('idUniversidad')->references('id')->on('universidad')->onDelete('cascade');
            $table->foreign('idIndicador')->references('id')->on('indicador')->onDelete('cascade');
            $table->foreign('idCurso')->references('id')->on('curso')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicador_universidad');
    }
};
