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
        $profesores = Profesor::all();
        $departamentos = Departamento::all();

        foreach ($departamentos as $departamento) {

            // 🔥 elegir 3 a 5 profesores por departamento
            $miembros = $profesores->random(min(5, $profesores->count()));

            foreach ($miembros as $profesor) {

                // 🔹 miembro actual
                MiembroDepartamento::create([
                    'id_profesor' => $profesor->id,
                    'id_departamento' => $departamento->id,
                    'fecha_inicio' => now()->subMonths(rand(1, 24)),
                    'fecha_fin' => null,
                    'habilitado' => true
                ]);

                // 🔥 OPCIONAL: historial (estuvo antes en otro depto)
                if (rand(0, 1)) {

                    $otroDepto = $departamentos
                        ->where('id', '!=', $departamento->id)
                        ->random();

                    MiembroDepartamento::create([
                        'id_profesor' => $profesor->id,
                        'id_departamento' => $otroDepto->id,
                        'fecha_inicio' => now()->subYears(3),
                        'fecha_fin' => now()->subYear(),
                        'habilitado' => false
                    ]);
                }
            }
        }
    }
}
