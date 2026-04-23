<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\SectorEstrategico;

class SectorEstrategicoSeeder extends Seeder
{
    public function run(): void
    {
        $sectores = [
            'Salud',
            'Educación',
            'Tecnología',
            'Energía',
            'Agricultura',
            'Industria',
            'Transporte',
            'Turismo',
            'Cultura',
            'Comunicaciones'
        ];

        foreach ($sectores as $nombre) {
            SectorEstrategico::create([
                'nombre' => $nombre
            ]);
        }
    }
}
