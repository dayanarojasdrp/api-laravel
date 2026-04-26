<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JefeDepartamento;
use App\Models\Departamento;
use App\Models\MiembroDepartamento;

class JefeDepartamentoSeeder extends Seeder
{
    public function run(): void
    {


            JefeDepartamento::create([
                'id_departamento' => 1,
                'id_profesor' => 2,
                'fecha_inicio' => now()->subYears(),
                'fecha_fin' => null,
                'habilitado' => true
            ]);



            JefeDepartamento::create([
                'id_departamento' => 2,
                'id_profesor' => 4,
                'fecha_inicio' => now()->subYear(),
                'fecha_fin' => null,
                'habilitado' => true
            ]);
        }
    }

