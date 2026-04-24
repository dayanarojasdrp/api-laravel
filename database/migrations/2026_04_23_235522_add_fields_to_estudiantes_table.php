<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {

            $table->string('apellidos')->after('nombre');
            $table->string('numero_carnet')->after('apellidos');

        });
    }

    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {

            $table->dropColumn(['apellidos', 'numero_carnet']);

        });
    }
};
