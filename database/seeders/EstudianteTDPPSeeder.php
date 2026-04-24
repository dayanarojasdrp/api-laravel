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

        if ($estudiantes->isEmpty() || $tdpps->isEmpty()) {
            return;
        }

        foreach ($estudiantes as $estudiante) {

            // 🔥 actual
            $tdpp = $tdpps->random();

            EstudianteTDPP::create([
                'estudiante_id' => $estudiante->id,
                'td_pp_id' => $tdpp->id,
                'fecha' => now()->subDays(rand(0, 200))
            ]);

            // 🔥 historial opcional
            if ($tdpps->count() > 1 && rand(0, 1)) {

                $otro = $tdpps->where('id', '!=', $tdpp->id);

                if ($otro->isNotEmpty()) {
                    $otro = $otro->random();

                    EstudianteTDPP::create([
                        'estudiante_id' => $estudiante->id,
                        'td_pp_id' => $otro->id,
                        'fecha' => now()->subDays(rand(201, 400))
                    ]);
                }
            }
        }
    }
}
