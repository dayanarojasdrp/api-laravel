<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\ProgFormacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartamentoProgFormSeeder extends Seeder
{
    public function run()
    {
        $departamentos = Departamento::whereIn('abreviatura', ['DMAT', 'DQUI', 'DFIS'])
            ->pluck('id', 'abreviatura');

        $programas = ProgFormacion::whereIn('abreviatura', ['M', 'LQ', 'F'])
            ->pluck('id', 'abreviatura');

        $relaciones = [
            ['departamento' => 'DMAT', 'programa' => 'M'],
            ['departamento' => 'DQUI', 'programa' => 'LQ'],
            ['departamento' => 'DFIS', 'programa' => 'F'],
        ];

        foreach ($relaciones as $relacion) {
            $departamentoId = $departamentos[$relacion['departamento']] ?? null;
            $programaId = $programas[$relacion['programa']] ?? null;

            if (!$departamentoId || !$programaId) {
                continue;
            }

            DB::table('departamento_prog_d_form')->updateOrInsert(
                ['id_prog_form' => $programaId],
                [
                    'uuid' => Str::uuid(),
                    'id_departamento' => $departamentoId,
                    'id_prog_form' => $programaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
