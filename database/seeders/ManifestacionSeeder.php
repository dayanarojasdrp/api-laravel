<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Manifestacion;
use App\Models\Edicion;

class ManifestacionSeeder extends Seeder
{
    public function run()
    {
        $ediciones = Edicion::all();

        foreach ($ediciones as $edicion) {
            // puedes crear 1 o varias por edición
            Manifestacion::create([
                'edicion_id' => $edicion->id
            ]);
        }
    }
}
