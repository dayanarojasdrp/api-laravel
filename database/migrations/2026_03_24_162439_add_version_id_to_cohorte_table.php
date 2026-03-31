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
            $table->unsignedBigInteger('version_id')->after('id');
            $table->foreign('version_id')
                  ->references('id')
                  ->on('version')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cohorte', function (Blueprint $table) {
            $table->dropForeign(['version_id']);
            $table->dropColumn('version_id');
        });
    }
};
