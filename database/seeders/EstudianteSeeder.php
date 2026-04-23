<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        Estudiante::create(['nombre' => 'Juan Perez']);
        Estudiante::create(['nombre' => 'Maria Lopez']);
        Estudiante::create(['nombre' => 'Carlos Sanchez']);
        Estudiante::create(['nombre' => 'Ana Martinez']);
        Estudiante::create(['nombre' => 'Luis Gomez']);
    }
}
