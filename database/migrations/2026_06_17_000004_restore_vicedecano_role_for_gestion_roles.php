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
            DB::statement("ALTER TABLE user_app_access MODIFY role ENUM('admin', 'vicedecano_docente', 'vicerrector_docente', 'decano', 'jefe_departamento', 'rector') NOT NULL");
        }

        DB::table('user_app_access')
            ->where('application_code', 'gestion_roles')
            ->where('role', 'vicerrector_docente')
            ->update(['role' => 'vicedecano_docente']);
    }

    public function down(): void
    {
        if (!Schema::hasTable('user_app_access') || !Schema::hasColumn('user_app_access', 'role')) {
            return;
        }

        DB::table('user_app_access')
            ->where('application_code', 'gestion_roles')
            ->where('role', 'vicedecano_docente')
            ->update(['role' => 'vicerrector_docente']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE user_app_access MODIFY role ENUM('admin', 'vicerrector_docente', 'decano', 'jefe_departamento', 'rector') NOT NULL");
        }
    }
};
