<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('servicio_user_tokens');
        Schema::dropIfExists('servicio_users');
    }

    public function down(): void
    {
        // Intencionalmente vacío: se eliminan tablas temporales de autenticación del servicio.
    }
};

