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
        Profesor::insert([
            [
                'nombre' => 'Juan',
                'apellidos' => 'Pérez García',
                'idCatDocente' => 1,
                'idCatCientifica' => 2,
                'grado_titulo_id' => 1
            ],
            [
                'nombre' => 'María',
                'apellidos' => 'González López',
                'idCatDocente' => 2,
                'idCatCientifica' => 1,
                'grado_titulo_id' => 2
            ],
            [
                'nombre' => 'Carlos',
                'apellidos' => 'Rodríguez Díaz',
                'idCatDocente' => 1,
                'idCatCientifica' => 3,
                'grado_titulo_id' => 3
            ],
            [
                'nombre' => 'Ana',
                'apellidos' => 'Martínez Torres',
                'idCatDocente' => 3,
                'idCatCientifica' => 2,
                'grado_titulo_id' => 1
            ],
            [
                'nombre' => 'Luis',
                'apellidos' => 'Hernández Castro',
                'idCatDocente' => 2,
                'idCatCientifica' => 1,
                'grado_titulo_id' => 2
            ],
            [
                'nombre' => 'Sofía',
                'apellidos' => 'Ramírez Peña',
                'idCatDocente' => 1,
                'idCatCientifica' => 2,
                'grado_titulo_id' => 3
            ],
            [
                'nombre' => 'Miguel',
                'apellidos' => 'Torres León',
                'idCatDocente' => 3,
                'idCatCientifica' => 1,
                'grado_titulo_id' => 1
            ],
            [
                'nombre' => 'Laura',
                'apellidos' => 'Díaz Morales',
                'idCatDocente' => 2,
                'idCatCientifica' => 3,
                'grado_titulo_id' => 2
            ],
            [
                'nombre' => 'Pedro',
                'apellidos' => 'Castro Ruiz',
                'idCatDocente' => 1,
                'idCatCientifica' => 2,
                'grado_titulo_id' => 3
            ],
            [
                'nombre' => 'Elena',
                'apellidos' => 'Suárez Navarro',
                'idCatDocente' => 3,
                'idCatCientifica' => 1,
                'grado_titulo_id' => 1
            ],
        ]);
    }
}
