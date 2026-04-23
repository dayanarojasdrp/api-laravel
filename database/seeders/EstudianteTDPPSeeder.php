<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\EstudianteTDPP;
use App\Models\Estudiante;
use App\Models\TD_PP;

class EstudianteTDPPSeeder extends Seeder
{
    public function run(): void
    {
        $estudiantes = Estudiante::all();
        $tdpps = TD_PP::all();

        foreach ($estudiantes as $estudiante) {

            // 🔥 cada estudiante tiene 1 TD_PP
            $tdpp = $tdpps->random();

            EstudianteTDPP::create([
                'estudiante_id' => $estudiante->id,
                'td_pp_id' => $tdpp->id,
                'fecha' => now()->subDays(rand(0, 200))
            ]);

            // 🔥 OPCIONAL: historial (participó en otro TD_PP antes)
            if (rand(0, 1)) {
                $otro = $tdpps->where('id', '!=', $tdpp->id)->random();

                EstudianteTDPP::create([
                    'estudiante_id' => $estudiante->id,
                    'td_pp_id' => $otro->id,
                    'fecha' => now()->subDays(rand(201, 400))
                ]);
            }
        }
    }
}
