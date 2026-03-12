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
        Schema::create('universidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('abreviatura');
            $table->string('nivelDeAcreditacion');
            $table->unsignedBigInteger('id_provincia');
            $table->unsignedBigInteger('id_municipio');
            $table->unsignedBigInteger('id_profesor');
            $table->timestamps();
        });
        Schema::table('universidad', function (Blueprint $table){
            $table->string('direccion')->nullable()->after('nivelDeAcreditacion');
            $table->foreign('id_municipio')->references('id')->on('municipio');
            $table->foreign('id_provincia')->references('id')->on('provincia');
            $table->foreign('id_profesor')->references('id')->on('profesor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universidad');
    }
};
