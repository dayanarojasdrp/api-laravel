<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PPA;
use App\Models\PpaHistorial;
use App\Models\Profesor;
use App\Models\AnoAcademico;
use App\Models\Curso;

class PPASeeder extends Seeder
{
    public function run(): void
    {
        $profesores = Profesor::all();
        $anos = AnoAcademico::all();
        $cursos = Curso::all();

      foreach ($anos as $ano) {

    $curso = $cursos->random();
    $profesor = $profesores->random();

    // 🔹 SOLO 1 PPA (activo)
    PPA::create([
        'id_profesor' => $profesor->id,
        'id_a_academico' => $ano->id,
        'id_curso' => $curso->id,
        'finished_at' => null
    ]);

    // 🔹 HISTORIAL
    PpaHistorial::create([
        'id_profesor' => $profesor->id,
        'id_a_academico' => $ano->id,
        'id_curso' => $curso->id,
        'accion' => 'designado',
        'fecha_accion' => now()
    ]);
}
    }
}
