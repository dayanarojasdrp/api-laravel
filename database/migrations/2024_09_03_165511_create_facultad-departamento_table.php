<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facultad_departamento', function (Blueprint $table) {

            $table->uuid('uuid');
            $table->unsignedBigInteger('id_departamento');
            $table->unsignedBigInteger('id_facultad');
            $table->timestamps();

            $table->primary('uuid');

            // 🔥 relaciones
            $table->foreign('id_departamento')
                ->references('id')
                ->on('departamento')
                ->onDelete('cascade');

            $table->foreign('id_facultad')
                ->references('id')
                ->on('facultad')
                ->onDelete('cascade');

            // 🔥 CLAVE: un departamento solo puede estar en UNA facultad
            $table->unique('id_departamento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facultad_departamento');
    }
};
