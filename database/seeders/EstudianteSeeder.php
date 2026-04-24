<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudianteSeeder extends Seeder
{


public function run(): void
{
    Estudiante::create([
        'nombre' => 'Juan',
        'apellidos' => 'Pérez García',
        'numero_carnet' => '20230001'
    ]);

    Estudiante::create([
        'nombre' => 'María',
        'apellidos' => 'López Díaz',
        'numero_carnet' => '20230002'
    ]);

    Estudiante::create([
        'nombre' => 'Carlos',
        'apellidos' => 'Sánchez Ruiz',
        'numero_carnet' => '20230003'
    ]);

    Estudiante::create([
        'nombre' => 'Ana',
        'apellidos' => 'Martínez Torres',
        'numero_carnet' => '20230004'
    ]);

    Estudiante::create([
        'nombre' => 'Luis',
        'apellidos' => 'Gómez Herrera',
        'numero_carnet' => '20230005'
    ]);
}
}
