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
        Schema::table('cohorte', function (Blueprint $table) {

            
            $table->dropForeign(['curso_inicio']);
            $table->dropForeign(['curso_fin']);

            
            $table->foreign('curso_inicio')
                  ->references('id')
                  ->on('curso')
                  ->onDelete('cascade');

            $table->foreign('curso_fin')
                  ->references('id')
                  ->on('curso')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('cohorte', function (Blueprint $table) {

            
            $table->dropForeign(['curso_inicio']);
            $table->dropForeign(['curso_fin']);

            
            $table->foreign('curso_inicio')
                  ->references('id')
                  ->on('curso')
                  ->onDelete('no action');

            $table->foreign('curso_fin')
                  ->references('id')
                  ->on('curso')
                  ->onDelete('no action');
        });
    }
};
