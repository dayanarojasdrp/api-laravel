<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('edicion_curso', function (Blueprint $table) {
    $table->id(); // 👈 AHORA SÍ necesitas id

    $table->foreignId('edicion_id')->constrained('ediciones')->cascadeOnDelete();
    $table->foreignId('curso_id')->constrained('curso')->cascadeOnDelete();

    $table->timestamp('fecha_inicio')->nullable();
    $table->timestamp('fecha_fin')->nullable();

    $table->timestamps(); // 👈 MUY IMPORTANTE
});
    }

    public function down(): void
    {
        Schema::dropIfExists('edicion_curso');
    }
};
