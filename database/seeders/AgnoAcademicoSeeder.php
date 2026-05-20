<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProgFormacion;

class AgnoAcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programas = ProgFormacion::whereIn('abreviatura', ['II', 'CC', 'F', 'M', 'LQ'])
            ->pluck('id');

        foreach ($programas as $programaId) {
            foreach (['1ro', '2do', '3ro', '4to'] as $identificador) {
                DB::table('a_academico')->updateOrInsert(
                    [
                        'identificador' => $identificador,
                        'id_prog_form' => $programaId,
                    ],
                    [
                        'identificador' => $identificador,
                        'id_prog_form' => $programaId,
                    ]
                );
            }
        }
    }
}
