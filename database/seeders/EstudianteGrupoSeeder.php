<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\EstudianteGrupo;
use App\Models\Estudiante;
use App\Models\Grupo;

class EstudianteGrupoSeeder extends Seeder
{
    public function run(): void
    {
        $estudiantes = Estudiante::all();
        $grupos = Grupo::all();

        foreach ($estudiantes as $estudiante) {

            // 🔥 cada estudiante tiene 1 grupo (o máximo 2 si quieres historial)
            $grupo = $grupos->random();

            EstudianteGrupo::create([
                'estudiante_id' => $estudiante->id,
                'grupo_id' => $grupo->id,
                'fecha' => now()->subDays(rand(0, 200))
            ]);

            // 🔥 OPCIONAL: historial (cambio de grupo)
            if (rand(0, 1)) {
                $otroGrupo = $grupos->where('id', '!=', $grupo->id)->random();

                EstudianteGrupo::create([
                    'estudiante_id' => $estudiante->id,
                    'grupo_id' => $otroGrupo->id,
                    'fecha' => now()->subDays(rand(201, 400))
                ]);
            }
        }
    }
}
