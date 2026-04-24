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
        // 🔥 crear 5 grupos
        for ($i = 0; $i < 5; $i++) {
            Grupo::create(); // solo id
        }
    }
}
