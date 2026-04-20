<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CohorteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cohorte')->insert([
            [
                'id' => 1,
                'curso_inicio' => 1,
                'curso_fin' => 5,
                'version_id' => 1, // ✅ IMPORTANTE
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'curso_inicio' => 2,
                'curso_fin' => 6,
                'version_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}

