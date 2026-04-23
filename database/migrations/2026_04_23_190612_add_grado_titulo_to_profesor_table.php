<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profesor', function (Blueprint $table) {

            $table->foreignId('grado_titulo_id')
                  ->nullable()
                  ->after('idCatCientifica')
                  ->constrained('grado_titulos')
                  ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('profesor', function (Blueprint $table) {

            $table->dropForeign(['grado_titulo_id']);
            $table->dropColumn('grado_titulo_id');

        });
    }
};
