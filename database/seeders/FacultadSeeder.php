<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facultad;

class FacultadSeeder extends Seeder
{
    public function run(): void
    {
        Facultad::create([
            'nombre' => 'Facultad de Matemática',
            'abreviatura' => 'MAT'
        ]);

        Facultad::create([
            'nombre' => 'Facultad de Química',
            'abreviatura' => 'QUI'
        ]);
    }
}
