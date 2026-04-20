<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        Departamento::create([
            'nombre' => 'Departamento de Matemática',
            'abreviatura' => 'DMAT'
        ]);
        Departamento::create([
            'nombre' => 'Departamento de Fisica',
            'abreviatura' => 'DFIS'
        ]);

        Departamento::create([
            'nombre' => 'Departamento de Química',
            'abreviatura' => 'DQUI'
        ]);
    }
}
