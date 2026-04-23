<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\GradoTitulo;

class GradoTituloSeeder extends Seeder
{
    public function run(): void
    {
        $grados = [
            'Técnico Medio',
            'Técnico Superior',
            'Licenciatura',
            'Ingeniería',
            'Maestría',
            'Especialidad',
            'Doctorado'
        ];

        foreach ($grados as $nombre) {
            GradoTitulo::create([
                'nombre' => $nombre
            ]);
        }
    }
}
