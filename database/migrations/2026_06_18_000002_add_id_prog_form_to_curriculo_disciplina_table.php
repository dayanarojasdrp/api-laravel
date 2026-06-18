<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('curriculo_disciplina', function (Blueprint $table) {
            if (!Schema::hasColumn('curriculo_disciplina', 'id_prog_form')) {
                $table->unsignedBigInteger('id_prog_form')->nullable()->after('id_disciplina');
                $table->foreign('id_prog_form')
                    ->references('id')
                    ->on('programa_de_formacion')
                    ->nullOnDelete();
                $table->index(['id_curriculo', 'id_disciplina', 'id_prog_form'], 'curr_disc_prog_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('curriculo_disciplina', function (Blueprint $table) {
            if (Schema::hasColumn('curriculo_disciplina', 'id_prog_form')) {
                $table->dropForeign(['id_prog_form']);
                $table->dropIndex('curr_disc_prog_idx');
                $table->dropColumn('id_prog_form');
            }
        });
    }
};
