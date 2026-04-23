<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ProfesorGuia;
use App\Models\Profesor;
use App\Models\Grupo;

class ProfesorGuiaSeeder extends Seeder
{
    public function run(): void
    {
        $profesores = Profesor::all();
        $grupos = Grupo::all();

        // ⚠️ seguridad
        if ($profesores->isEmpty() || $grupos->isEmpty()) {
            return;
        }

        foreach ($grupos as $grupo) {

            // 🔥 seleccionar 2 profesores distintos
            $seleccionados = $profesores->random(min(2, $profesores->count()));

            if ($seleccionados->count() < 2) {
                continue;
            }

            $anterior = $seleccionados->first();
            $actual = $seleccionados->last();

            // ============================
            // 🔴 HISTÓRICO
            // ============================
            ProfesorGuia::create([
                'id_profesor' => $anterior->id,
                'id_grupo' => $grupo->id,
                'fecha_inicio' => now()->subYears(2),
                'fecha_fin' => now()->subYear(),
                'habilitado' => false
            ]);

            // ============================
            // 🟢 ACTUAL
            // ============================
            ProfesorGuia::create([
                'id_profesor' => $actual->id,
                'id_grupo' => $grupo->id,
                'fecha_inicio' => now()->subYear(),
                'fecha_fin' => null,
                'habilitado' => true
            ]);
        }
    }
}
