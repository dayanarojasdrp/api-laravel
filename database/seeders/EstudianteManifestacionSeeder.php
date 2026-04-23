<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstudianteManifestacion;
use App\Models\Estudiante;
use App\Models\Manifestacion;

class EstudianteManifestacionSeeder extends Seeder
{
    public function run(): void
    {
        $estudiantes = Estudiante::all();
        $manifestaciones = Manifestacion::all();

        foreach ($estudiantes as $estudiante) {

            // 🔥 solo 1 o 2 manifestaciones por estudiante
            $randomManifestaciones = $manifestaciones->random(
                min(2, $manifestaciones->count())
            );

            foreach ($randomManifestaciones as $manifestacion) {
                EstudianteManifestacion::create([
                    'estudiante_id' => $estudiante->id,
                    'manifestacion_id' => $manifestacion->id,
                    'fecha' => now()->subDays(rand(0, 100))
                ]);
            }
        }
    }
}
