<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DecanoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('decano')->insert([
            'uuid' => Str::uuid(),
            'id_facultad' => 1,   // 👈 asegúrate que exista
            'id_profesor' => 1,   // 👈 asegúrate que exista
            'id_curso' => 27,      // 👈 asegúrate que exista
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
