<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\AnoGrupo;
use App\Models\AnoAcademico;
use App\Models\Grupo;

class AnoGrupoSeeder extends Seeder
{
    public function run(): void
    {
        $anos = AnoAcademico::all();
        $grupos = Grupo::all();

        foreach ($anos as $ano) {

            // 🔥 solo 1 o 2 grupos por año (EVITA EXPLOSIÓN)
            $gruposRandom = $grupos->random(
                min(2, $grupos->count())
            );

            foreach ($gruposRandom as $grupo) {

                AnoGrupo::create([
                    'ano_academico_id' => $ano->id,
                    'grupo_id' => $grupo->id,
                    'fecha' => now()->subDays(rand(0, 200))
                ]);

            }
        }
    }
}
