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
        Schema::create('plan-estudio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_prog_form')->nullable();
            $table->timestamps();
            $table->foreign('id_prog_form')->references('id')->on('programa_de_formacion')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan-estudio');
    }
};
