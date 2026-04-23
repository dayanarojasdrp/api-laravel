<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Edicion;

class EdicionSeeder extends Seeder
{
    public function run()
    {
        Edicion::create([
            'tipo_id' => 1
        ]);

        Edicion::create([
            'tipo_id' => 2
        ]);

        Edicion::create([
            'tipo_id' => 3
        ]);
    }
}
