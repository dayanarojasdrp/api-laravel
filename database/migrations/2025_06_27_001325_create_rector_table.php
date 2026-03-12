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
        Schema::create('rector', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->unsignedBigInteger('id_universidad');
            $table->unsignedBigInteger('id_profesor');
            $table->unsignedBigInteger('id_curso');
            $table->timestamps();
            $table->primary('uuid');
            $table->foreign('id_universidad')->references('id')->on('universidad')->onDelete('cascade');
            $table->foreign('id_profesor')->references('id')->on('profesor')->onDelete('cascade');
            $table->foreign('id_curso')->references('id')->on('curso')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rector');
    }
};
