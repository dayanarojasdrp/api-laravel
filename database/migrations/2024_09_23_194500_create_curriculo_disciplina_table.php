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
        Schema::create('curriculo_disciplina', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('id_curriculo');
            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_disciplina');
            $table->timestamps();
            $table->foreign('id_curso')->references('id')->on('curso')->onDelete('cascade');
            $table->foreign('id_curriculo')->references('id')->on('curriculo')->onDelete('cascade');
            $table->foreign('id_disciplina')->references('id')->on('disciplina')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculo_disciplina');
    }
};
