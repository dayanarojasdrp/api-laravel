<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\AnoGrupo;
use App\Models\AnoAcademico;
use App\Models\Grupo;


use Illuminate\Support\Facades\DB;

class AnoGrupoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ano_grupo')->truncate();

        DB::table('ano_grupo')->insert([
            // 🔹 Grupo 1 → Año 1ro
            [
                'ano_academico_id' => 1,
                'grupo_id' => 1,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔹 Grupo 2 → Año 2do
            [
                'ano_academico_id' => 2,
                'grupo_id' => 2,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔹 Grupo 3 → Año 3ro
            [
                'ano_academico_id' => 3,
                'grupo_id' => 3,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔹 Grupo 4 → Año 4to
            [
                'ano_academico_id' => 4,
                'grupo_id' => 4,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔹 Grupo 5 → Año 1ro (otro programa por ejemplo)
            [
                'ano_academico_id' => 1,
                'grupo_id' => 5,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
