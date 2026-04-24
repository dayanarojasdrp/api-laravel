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

        if ($estudiantes->isEmpty() || $manifestaciones->isEmpty()) {
            return;
        }

        foreach ($estudiantes as $estudiante) {

            // 🔥 1 o 2 manifestaciones
            $cantidad = min(2, $manifestaciones->count());

            $seleccionadas = $manifestaciones->random($cantidad);

            // 🔥 asegurar colección
            if (!($seleccionadas instanceof \Illuminate\Support\Collection)) {
                $seleccionadas = collect([$seleccionadas]);
            }

            foreach ($seleccionadas as $manifestacion) {
                EstudianteManifestacion::create([
                    'estudiante_id' => $estudiante->id,
                    'manifestacion_id' => $manifestacion->id,
                    'fecha' => now()->subDays(rand(0, 100))
                ]);
            }
        }
    }
}
