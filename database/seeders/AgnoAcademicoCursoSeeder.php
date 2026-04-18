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
            'id_cohorte' => '097fb10e-0f8d-4a53-b5b6-23b10503d75e'
        ]);
    }
}
