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
            'id_cohorte' => 'bad4aeca-ecdb-4b48-b23e-a1b97dab9da6'
        ]);
    }
}
