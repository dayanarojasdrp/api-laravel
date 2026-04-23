<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up()
    {
        Schema::create('ediciones', function (Blueprint $table) {
            $table->id();

            // 🔹 llave foránea
            $table->foreignId('tipo_id')
                  ->constrained('tipos') // 👈 apunta a tabla tipos
                  ->onDelete('cascade'); // opcional pero recomendado
        });
    }

    public function down()
    {
        Schema::dropIfExists('ediciones');
    }
};
