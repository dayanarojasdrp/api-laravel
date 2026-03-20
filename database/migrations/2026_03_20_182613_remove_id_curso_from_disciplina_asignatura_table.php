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
        Schema::table('disciplina_asignatura', function (Blueprint $table) {
             $table->dropForeign(['id_curso']);
             $table->dropColumn('id_curso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplina_asignatura', function (Blueprint $table) {
            $table->unsignedBigInteger('id_curso')->nullable();

            $table->foreign('id_curso')
                ->references('id')
                ->on('curso')
                ->onDelete('cascade');
        });
    }
};
