<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanEstudioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plan-estudio')->insert([
            [
                'id' => 1,
                'nombre' => 'Plan 2020',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'nombre' => 'Plan 2021',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
