<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asignatura', function (Blueprint $table) {
            $table->unsignedInteger('horas_clase')->default(0)->after('fondo_tiempo');
            $table->unsignedInteger('horas_practica_laboral')->default(0)->after('horas_clase');
        });

        DB::table('asignatura')->update([
            'horas_clase' => DB::raw('CAST(fondo_tiempo AS UNSIGNED)'),
            'horas_practica_laboral' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignatura', function (Blueprint $table) {
            $table->dropColumn(['horas_clase', 'horas_practica_laboral']);
        });
    }
};
