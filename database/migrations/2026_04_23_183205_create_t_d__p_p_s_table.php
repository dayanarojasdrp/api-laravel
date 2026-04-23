<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('td_pp', function (Blueprint $table) {

            $table->id();

            $table->string('desarrollo_local');

            $table->foreignId('sector_estrategico_id')
                  ->constrained('sector_estrategicos')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('td_pp');
    }
};
