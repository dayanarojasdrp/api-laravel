<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_app_access', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('application_code');
            $table->enum('role', [
                'admin',
                'vicedecano_docente',
                'decano',
                'jefe_departamento',
                'rector',
            ]);
            $table->unsignedBigInteger('facultad_id')->nullable();
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['username', 'application_code', 'active']);
            $table->index(['application_code', 'facultad_id', 'active']);
            $table->index(['application_code', 'role', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_app_access');
    }
};
