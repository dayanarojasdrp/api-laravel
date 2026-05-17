<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Grupo;




class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        $gruposNecesarios = 5;
        $gruposActuales = Grupo::count();

        for ($i = $gruposActuales; $i < $gruposNecesarios; $i++) {
            $grupo = new Grupo();
            $grupo->save();
        }
    }
}
