<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Grupo;




class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {

            $grupo = new Grupo();
            $grupo->save(); // 👈 esto SIEMPRE funciona
        }
    }
}
