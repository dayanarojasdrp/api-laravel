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
        Schema::create('cohorte', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('curso_inicio');
            $table->unsignedBigInteger('curso_fin');
            $table->timestamps();
            $table->foreign('curso_inicio')->references('id')->on('curso')->onDelete('no action');
            $table->foreign('curso_fin')->references('id')->on('curso')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohorte');
    }
};
