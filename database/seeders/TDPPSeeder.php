<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TD_PP;
use App\Models\SectorEstrategico;

class TDPPSeeder extends Seeder
{
    public function run(): void
    {
        $sectores = SectorEstrategico::all();

        $desarrollos = [
            'Proyecto comunitario',
            'Innovación tecnológica',
            'Desarrollo sostenible',
            'Programa educativo local',
            'Mejora de infraestructura',
            'Plan agrícola estratégico',
            'Transformación digital',
            'Gestión ambiental',
            'Desarrollo social',
            'Proyecto cultural'
        ];

        foreach ($desarrollos as $nombre) {

            // 🔥 asigna sector aleatorio
            $sector = $sectores->random();

            TD_PP::create([
                'desarrollo_local' => $nombre,
                'sector_estrategico_id' => $sector->id
            ]);
        }
    }
}
