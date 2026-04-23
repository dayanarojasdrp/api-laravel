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
        Schema::create('manifestaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('edicion_id');

            $table->foreign('edicion_id')
                  ->references('id')
                  ->on('ediciones')
                  ->onDelete('cascade'); // 🔥 importante
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifestaciones');
    }
};
