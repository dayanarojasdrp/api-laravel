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
        Schema::create('modificacion', function (Blueprint $table) {
            $table->id();
           $table->string('nombre');
           $table->unsignedBigInteger('version_id');
            $table->timestamps();
            $table->foreign('version_id')->references('id')->on('version')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modificacion');
    }
};
