<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamento_prog_d_form', function (Blueprint $table) {

            $table->uuid('uuid');
            $table->unsignedBigInteger('id_departamento');
            $table->unsignedBigInteger('id_prog_form');
            $table->timestamps();

            $table->primary('uuid');

            // 🔥 relaciones
            $table->foreign('id_departamento')
                ->references('id')
                ->on('departamento')
                ->onDelete('cascade');

            $table->foreign('id_prog_form')
                ->references('id')
                ->on('programa_de_formacion')
                ->onDelete('cascade');

            // 🔥 CLAVE: un programa solo puede estar en un departamento
            $table->unique('id_prog_form');
        });
    }

    public function down(): void
    {
        // 🔥 nombre correcto (tenías error aquí)
        Schema::dropIfExists('departamento_prog_d_form');
    }
};
