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
        Schema::create('plan_de_estudio_programa_de_formacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('programa_de_formacion_id');
            $table->foreign('programa_de_formacion_id', 'fk_prog_formacion')->references('id')->on('programa_de_formacion')->cascadeOnDelete();
            $table->unsignedBigInteger('plan_estudio_id');
            $table->foreign('plan_estudio_id', 'fk_plan_estudio')->references('id')->on('plan-estudio')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_de_estudio_programa_de_formacion');
    }
};
