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
       Schema::table('estudiantes', function (Blueprint $table) {
    $table->unique('numero_carnet');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('numero_carnet', function (Blueprint $table) {
            //
        });
    }
};
