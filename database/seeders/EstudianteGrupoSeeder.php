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
        $carnets = [
            '20230001',
            '20230002',
            '20230003',
            '20230004',
            '20230005',
        ];

        $estudiantes = Estudiante::whereIn('numero_carnet', $carnets)
            ->orderBy('numero_carnet')
            ->get();

        while (Grupo::count() < $estudiantes->count()) {
            $grupo = new Grupo();
            $grupo->save();
        }

        $grupos = Grupo::orderBy('id')
            ->take($estudiantes->count())
            ->get();

        foreach ($estudiantes as $index => $estudiante) {
            if (!isset($grupos[$index])) {
                continue;
            }

            DB::table('estudiante_grupo')->updateOrInsert(
                ['estudiante_id' => $estudiante->id],
                [
                    'estudiante_id' => $estudiante->id,
                    'grupo_id' => $grupos[$index]->id,
                    'fecha' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
