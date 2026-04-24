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

        // 🔥 seguridad
        if ($anos->isEmpty() || $grupos->isEmpty()) {
            return;
        }

        foreach ($anos as $ano) {

            // 🔥 cantidad segura (1 o 2)
            $cantidad = min(2, $grupos->count());

            $seleccionados = $grupos->random($cantidad);

            // 🔥 asegurar colección SIEMPRE
            if (!($seleccionados instanceof \Illuminate\Support\Collection)) {
                $seleccionados = collect([$seleccionados]);
            }

            foreach ($seleccionados as $grupo) {

                AnoGrupo::create([
                    'ano_academico_id' => $ano->id,
                    'grupo_id' => $grupo->id,
                    'fecha' => now()->subDays(rand(0, 200))
                ]);
            }
        }
    }
}
