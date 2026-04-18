<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;

class ProfesorSeeder extends Seeder
{
    public function run(): void
    {
        Profesor::create([
            'nombre' => 'Juan',
            'apellidos' => 'Pérez Gómez',
            'idCatDocente' => 1,      // ID existente en categoria_docente
            'idCatCientifica' => 2,   // ID existente en categoria_cientifica
        ]);
    }
}
