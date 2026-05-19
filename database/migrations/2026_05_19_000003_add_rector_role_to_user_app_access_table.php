<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_app_access') || !Schema::hasColumn('user_app_access', 'role')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE user_app_access MODIFY role ENUM('admin', 'vicedecano_docente', 'decano', 'jefe_departamento', 'rector') NOT NULL");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('user_app_access') || !Schema::hasColumn('user_app_access', 'role')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE user_app_access MODIFY role ENUM('admin', 'vicedecano_docente', 'decano', 'jefe_departamento') NOT NULL");
        }
    }
};
