<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\EstudianteGrupo;
use App\Models\Estudiante;
use App\Models\Grupo;

use Illuminate\Support\Facades\DB;

class EstudianteGrupoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estudiante_grupo')->insert([
            [
                'estudiante_id' => 1,
                'grupo_id' => 1,
                'fecha' => now()
            ],
            [
                'estudiante_id' => 2,
                'grupo_id' => 2,
                'fecha' => now()
            ],
            [
                'estudiante_id' => 3,
                'grupo_id' => 3,
                'fecha' => now()
            ],
            [
                'estudiante_id' => 4,
                'grupo_id' => 4,
                'fecha' => now()
            ],
            [
                'estudiante_id' => 5,
                'grupo_id' => 5,
                'fecha' => now()
            ],
        ]);
    }
}
