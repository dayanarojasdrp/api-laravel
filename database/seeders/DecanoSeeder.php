<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Decano;
use App\Models\Profesor;
use App\Models\Facultad;

class DecanoSeeder extends Seeder
{
    public function run(): void
    {




            Decano::create([
                'id_facultad' => 1,
                'id_profesor' => 1,
                'fecha_inicio' => now()->subYear(),
                'fecha_fin' => null,
                'habilitado' => true
            ]);
        }
    }

