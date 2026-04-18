<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VersionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('version')->insert([
            [
                'id' => 1,
                'nombre' => 'Version 1',
                'plan_estudio_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
