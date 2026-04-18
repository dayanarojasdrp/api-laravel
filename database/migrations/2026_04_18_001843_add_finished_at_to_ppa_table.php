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
        Schema::table('ppa', function (Blueprint $table) {
    $table->timestamp('finished_at')->nullable();
    $table->unique(['id_curso', 'id_a_academico']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppa', function (Blueprint $table) {
            //
        });
    }
};
