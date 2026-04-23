<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tipo;


class TipoSeeder extends Seeder
{
    public function run()
    {
        // crear 5 registros vacíos
        Tipo::create();
        Tipo::create();
        Tipo::create();
        Tipo::create();
        Tipo::create();
    }
}
