<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\EdicionCurso;
use App\Models\Edicion;
use App\Models\Curso;

class EdicionCursoSeeder extends Seeder
{
    public function run()
    {
        $ediciones = Edicion::all();
        $cursos = Curso::all();

        foreach ($ediciones as $edicion) {
            foreach ($cursos as $curso) {
                EdicionCurso::create([
                    'edicion_id' => $edicion->id,
                    'curso_id' => $curso->id,
                    'fecha_inicio' => now(),
                    'fecha_fin' => null
                ]);
            }
        }
    }
}
