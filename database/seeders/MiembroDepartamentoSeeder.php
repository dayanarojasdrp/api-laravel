<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\MiembroDepartamento;
use App\Models\Profesor;
use App\Models\Departamento;

class MiembroDepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        MiembroDepartamento::insert([

            // 🔵 MATEMÁTICA (id = 1)
            [
                'id_profesor' => 1,
                'id_departamento' => 1,
                'fecha_inicio' => now()->subYears(2),
                'fecha_fin' => null,
                'habilitado' => true
            ],
            [
                'id_profesor' => 2,
                'id_departamento' => 1,
                'fecha_inicio' => now()->subYears(1),
                'fecha_fin' => null,
                'habilitado' => true
            ],
            [
                'id_profesor' => 3,
                'id_departamento' => 1,
                'fecha_inicio' => now()->subMonths(8),
                'fecha_fin' => null,
                'habilitado' => true
            ],

            // 🔵 FÍSICA (id = 2)
            [
                'id_profesor' => 4,
                'id_departamento' => 2,
                'fecha_inicio' => now()->subYears(3),
                'fecha_fin' => null,
                'habilitado' => true
            ],
            [
                'id_profesor' => 5,
                'id_departamento' => 2,
                'fecha_inicio' => now()->subYears(2),
                'fecha_fin' => null,
                'habilitado' => true
            ],
            [
                'id_profesor' => 6,
                'id_departamento' => 2,
                'fecha_inicio' => now()->subMonths(6),
                'fecha_fin' => null,
                'habilitado' => true
            ],

            // 🔵 QUÍMICA (id = 3)
            [
                'id_profesor' => 7,
                'id_departamento' => 3,
                'fecha_inicio' => now()->subYears(1),
                'fecha_fin' => null,
                'habilitado' => true
            ],
            [
                'id_profesor' => 8,
                'id_departamento' => 3,
                'fecha_inicio' => now()->subMonths(10),
                'fecha_fin' => null,
                'habilitado' => true
            ],
            [
                'id_profesor' => 9,
                'id_departamento' => 3,
                'fecha_inicio' => now()->subMonths(4),
                'fecha_fin' => null,
                'habilitado' => true
            ],


            [
                'id_profesor' => 10,
                'id_departamento' => 3,
                'fecha_inicio' => now()->subYears(4),
                'fecha_fin' => null,
                'habilitado' => false
            ],

        ]);
    }
}
