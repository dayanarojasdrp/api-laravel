<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\AnoGrupo;
use App\Models\AnoAcademico;
use App\Models\Grupo;
use App\Models\ProgFormacion;


use Illuminate\Support\Facades\DB;

class AnoGrupoSeeder extends Seeder
{
    public function run(): void
    {
        $gruposNecesarios = 5;

        while (Grupo::count() < $gruposNecesarios) {
            Grupo::create([]);
        }

        $grupos = Grupo::orderBy('id')->take($gruposNecesarios)->get();

        $matematicaId = ProgFormacion::where('abreviatura', 'M')->value('id');
        $quimicaId = ProgFormacion::where('abreviatura', 'LQ')->value('id');

        $asignaciones = [
            ['identificador' => '1ro', 'id_prog_form' => $matematicaId],
            ['identificador' => '2do', 'id_prog_form' => $matematicaId],
            ['identificador' => '3ro', 'id_prog_form' => $matematicaId],
            ['identificador' => '1ro', 'id_prog_form' => $quimicaId],
            ['identificador' => '2do', 'id_prog_form' => $quimicaId],
        ];

        foreach ($asignaciones as $index => $asignacion) {
            $anoAcademicoId = AnoAcademico::where($asignacion)->value('id');

            if (!$anoAcademicoId || !isset($grupos[$index])) {
                continue;
            }

            DB::table('ano_grupo')->updateOrInsert(

                [
                    'ano_academico_id' => 9,
                    'grupo_id' => 1,
                    'fecha' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'ano_academico_id' => 10,
                    'grupo_id' => 2,
                    'fecha' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'ano_academico_id' => 11,
                    'grupo_id' => 3,
                    'fecha' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'ano_academico_id' => 13,
                    'grupo_id' => 4,
                    'fecha' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
