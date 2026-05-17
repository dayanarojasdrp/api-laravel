<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudianteSeeder extends Seeder
{


public function run(): void
{
    $estudiantes = [
        ['nombre' => 'Juan', 'apellidos' => 'Pérez García', 'numero_carnet' => '20230001'],
        ['nombre' => 'María', 'apellidos' => 'López Díaz', 'numero_carnet' => '20230002'],
        ['nombre' => 'Carlos', 'apellidos' => 'Sánchez Ruiz', 'numero_carnet' => '20230003'],
        ['nombre' => 'Ana', 'apellidos' => 'Martínez Torres', 'numero_carnet' => '20230004'],
        ['nombre' => 'Luis', 'apellidos' => 'Gómez Herrera', 'numero_carnet' => '20230005'],
    ];

    foreach ($estudiantes as $estudiante) {
        DB::table('estudiantes')->updateOrInsert(
            ['numero_carnet' => $estudiante['numero_carnet']],
            array_merge($estudiante, [
                'created_at' => now(),
                'updated_at' => now(),
            ])
        );
    }
}
}
