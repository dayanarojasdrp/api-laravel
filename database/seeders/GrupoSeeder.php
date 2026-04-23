<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 crear 10 grupos vacíos
        for ($i = 0; $i < 10; $i++) {
            Grupo::create();
        }
    }
}
