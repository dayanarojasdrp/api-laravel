<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('estudiante_td_pp', function (Blueprint $table) {

            $table->id();

            $table->foreignId('estudiante_id')
                  ->constrained('estudiantes')
                  ->cascadeOnDelete();

            $table->foreignId('td_pp_id')
                  ->constrained('td_pp')
                  ->cascadeOnDelete();

            // 🔥 HISTORIAL
            $table->timestamp('fecha')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiante_td_pp');
    }
};
