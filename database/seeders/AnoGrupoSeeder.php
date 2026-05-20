<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\AnoGrupo;
use App\Models\AnoAcademico;
use App\Models\Grupo;
use App\Models\ProgFormacion;


use Illuminate\Support\Facades\DB;

class AnoGrupoSeeder extends Seeder
{
    public function run(): void
    {
        $relaciones = [
            [
                'ano_academico_id' => 1,
                'grupo_id' => 1,
            ],
            [
                'ano_academico_id' => 5,
                'grupo_id' => 2,
            ],
            [
                'ano_academico_id' => 10,
                'grupo_id' => 3,
            ],
            [
                'ano_academico_id' => 14,
                'grupo_id' => 4,
            ],
            [
                'ano_academico_id' => 17,
                'grupo_id' => 5,
            ],
        ];

        DB::table('ano_grupo')
            ->whereIn('grupo_id', array_column($relaciones, 'grupo_id'))
            ->delete();

        foreach ($relaciones as $relacion) {
            DB::table('ano_grupo')->insert([
                'ano_academico_id' => $relacion['ano_academico_id'],
                'grupo_id' => $relacion['grupo_id'],
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
