<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;

use App\Models\CatDocente;
use App\Models\CatCientifica;
use App\Models\GradoTitulo;

class ProfesorSeeder extends Seeder
{
    public function run(): void
    {
        $catDocentes = CatDocente::all();
        $catCientificas = CatCientifica::all();
        $grados = GradoTitulo::all();

        $nombres = [
            'Juan', 'María', 'Carlos', 'Ana', 'Luis',
            'Pedro', 'Laura', 'Miguel', 'Sofía', 'José'
        ];

        $apellidos = [
            'Pérez', 'González', 'Rodríguez', 'López', 'Martínez',
            'Hernández', 'Díaz', 'Torres', 'Ramírez', 'Castro'
        ];

        for ($i = 0; $i < 20; $i++) {

            Profesor::create([
                'nombre' => $nombres[array_rand($nombres)],
                'apellidos' => $apellidos[array_rand($apellidos)],
                'idCatDocente' => $catDocentes->random()->id,
                'idCatCientifica' => $catCientificas->random()->id,
                'grado_titulo_id' => $grados->random()->id
            ]);
        }
    }
}
