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
        Schema::table('plan-estudio', function (Blueprint $table) {
            $table->dropForeign(['id_prog_form']); 
            $table->dropColumn('id_prog_form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan-estudio', function (Blueprint $table) {
             $table->unsignedBigInteger('id_prog_form')->nullable();

        $table->foreign('id_prog_form')
              ->references('id')
              ->on('programa_de_formacion')
              ->onDelete('cascade');
        });
    }
};
