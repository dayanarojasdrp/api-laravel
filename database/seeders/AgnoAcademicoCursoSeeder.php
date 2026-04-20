<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgnoAcademico_Curso;
use Illuminate\Support\Str;

class AgnoAcademicoCursoSeeder extends Seeder
{
    public function run(): void
    {
        AgnoAcademico_Curso::create([
            'id' => Str::uuid(),
            'id_curso' => 1,
            'id_a_academico' => 1,
            'id_cohorte' => 1

        ]);
        AgnoAcademico_Curso::create([
            'id' => Str::uuid(),
            'id_curso' => 1,
            'id_a_academico' => 5,
            'id_cohorte' => 1

        ]);
        AgnoAcademico_Curso::create([
            'id' => Str::uuid(),
            'id_curso' => 2,
            'id_a_academico' => 6,
            'id_cohorte' => 1

        ]);
        AgnoAcademico_Curso::create([
            'id' => Str::uuid(),
            'id_curso' => 3,
            'id_a_academico' => 7,
            'id_cohorte' => 1

        ]);
        AgnoAcademico_Curso::create([
            'id' => Str::uuid(),
            'id_curso' => 4,
            'id_a_academico' => 8,
            'id_cohorte' => 1

        ]);
    }
}
