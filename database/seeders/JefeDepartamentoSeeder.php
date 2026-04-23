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
        $departamentos = Departamento::all();

        foreach ($departamentos as $departamento) {

            // 🔥 obtener SOLO miembros de ese departamento
            $miembros = MiembroDepartamento::where('id_departamento', $departamento->id)
                ->where('habilitado', true)
                ->get();

            // ⚠️ si no hay miembros → saltar
            if ($miembros->count() < 2) {
                continue;
            }

            // 🔥 JEFE ANTERIOR
            $anterior = $miembros->random();

            JefeDepartamento::create([
                'id_departamento' => $departamento->id,
                'id_profesor' => $anterior->id_profesor,
                'fecha_inicio' => now()->subYears(3),
                'fecha_fin' => now()->subYear(),
                'habilitado' => false
            ]);

            // 🔥 JEFE ACTUAL (distinto)
            $actual = $miembros
                ->where('id_profesor', '!=', $anterior->id_profesor)
                ->random();

            JefeDepartamento::create([
                'id_departamento' => $departamento->id,
                'id_profesor' => $actual->id_profesor,
                'fecha_inicio' => now()->subYear(),
                'fecha_fin' => null,
                'habilitado' => true
            ]);
        }
    }
}
