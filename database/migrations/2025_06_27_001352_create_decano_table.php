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
      Schema::create('decano', function (Blueprint $table) {
    $table->uuid('uuid');
    $table->unsignedBigInteger('id_facultad');
    $table->unsignedBigInteger('id_profesor');
    $table->timestamps();

    $table->primary('uuid');

    $table->foreign('id_profesor')
        ->references('id')->on('profesor')
        ->onDelete('cascade');

    $table->foreign('id_facultad')
        ->references('id')->on('facultad')
        ->onDelete('cascade');

    // 🔥 ESTO ES LO IMPORTANTE
    $table->unique('id_facultad'); // 1 decano por facultad
    $table->unique('id_profesor'); // 1 profesor solo puede ser decano una vez
});

    }
    /**
     * Reverse the migrations.
     */
  public function down(): void
{
    Schema::dropIfExists('decano');
}
};
