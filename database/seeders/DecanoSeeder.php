<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Decano;
use App\Models\Profesor;
use App\Models\Facultad;

class DecanoSeeder extends Seeder
{
    public function run(): void
    {
        $profesores = Profesor::all();
        $facultades = Facultad::all();

        foreach ($facultades as $facultad) {

            // 🔥 HISTORIAL (decano anterior)
            $decanoAnterior = $profesores->random();

            Decano::create([
                'id_facultad' => $facultad->id,
                'id_profesor' => $decanoAnterior->id,
                'fecha_inicio' => now()->subYears(3),
                'fecha_fin' => now()->subYear(),
                'habilitado' => false
            ]);

            // 🔥 DECANO ACTUAL
            $decanoActual = $profesores
                ->where('id', '!=', $decanoAnterior->id)
                ->random();

            Decano::create([
                'id_facultad' => $facultad->id,
                'id_profesor' => $decanoActual->id,
                'fecha_inicio' => now()->subYear(),
                'fecha_fin' => null,
                'habilitado' => true
            ]);
        }
    }
}
