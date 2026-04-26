<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facultad;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacultadDepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $matematica = Facultad::where('abreviatura', 'MAT')->first();
        $quimica = Facultad::where('abreviatura', 'QUI')->first();

        $depMat = Departamento::where('abreviatura', 'DMAT')->first();
        $depQui = Departamento::where('abreviatura', 'DQUI')->first();
        $depFis = Departamento::where('abreviatura', 'DFIS')->first();
        // 🔵 Matemática → Departamento Matemática → Curso 1
        DB::table('facultad_departamento')->insert([
            'uuid' => Str::uuid(),
            'id_facultad' => $matematica->id,
            'id_departamento' => $depMat->id,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // 🔵 Matemática → Departamento de fisica → Curso 2
        DB::table('facultad_departamento')->insert([
            'uuid' => Str::uuid(),
            'id_facultad' => $matematica->id,
            'id_departamento' => $depFis->id,

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🔵 Química → Departamento Química → Curso 1
        DB::table('facultad_departamento')->insert([
            'uuid' => Str::uuid(),
            'id_facultad' => $quimica->id,
            'id_departamento' => $depQui->id,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
