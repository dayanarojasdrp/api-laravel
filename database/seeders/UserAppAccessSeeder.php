<?php

namespace Database\Seeders;

use App\Models\UserAppAccess;
use Illuminate\Database\Seeder;

class UserAppAccessSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin(UserAppAccess::APPLICATION_GESTION_ROLES);
        $this->seedAdmin(UserAppAccess::APPLICATION_GESTION_PLAN_ESTUDIO);
    }

    private function seedAdmin(string $applicationCode): void
    {
        $alreadyActive = UserAppAccess::where('username', 'usuario01')
            ->where('application_code', $applicationCode)
            ->where('role', 'admin')
            ->where('active', true)
            ->exists();

        if ($alreadyActive) {
            return;
        }

        UserAppAccess::where('application_code', $applicationCode)
            ->where('role', 'admin')
            ->where('active', true)
            ->update(['active' => false]);

        UserAppAccess::create([
            'username' => 'usuario01',
            'application_code' => $applicationCode,
            'role' => 'admin',
            'facultad_id' => null,
            'departamento_id' => null,
            'active' => true,
        ]);
    }
}
