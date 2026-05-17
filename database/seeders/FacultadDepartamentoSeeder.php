<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facultad;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacultadDepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $matematica = Facultad::where('abreviatura', 'MAT')->first();
        $quimica = Facultad::where('abreviatura', 'QUI')->first();

        $depMat = Departamento::where('abreviatura', 'DMAT')->first();
        $depQui = Departamento::where('abreviatura', 'DQUI')->first();
        $depFis = Departamento::where('abreviatura', 'DFIS')->first();
        $relaciones = [
            ['facultad' => $matematica, 'departamento' => $depMat],
            ['facultad' => $matematica, 'departamento' => $depFis],
            ['facultad' => $quimica, 'departamento' => $depQui],
        ];

        foreach ($relaciones as $relacion) {
            if (!$relacion['facultad'] || !$relacion['departamento']) {
                continue;
            }

            DB::table('facultad_departamento')->updateOrInsert(
                ['id_departamento' => $relacion['departamento']->id],
                [
                    'uuid' => Str::uuid(),
                    'id_facultad' => $relacion['facultad']->id,
                    'id_departamento' => $relacion['departamento']->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
