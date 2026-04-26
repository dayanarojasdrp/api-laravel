<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartamentoProgFormSeeder extends Seeder
{
    public function run()
    {
        DB::table('departamento_prog_d_form')->insert([

            [
                'uuid' => Str::uuid(),
                'id_departamento' => 1, // Matemática
                'id_prog_form' => 5,    // Licenciatura Matemática

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'uuid' => Str::uuid(),
                'id_departamento' => 3, // Química
                'id_prog_form' => 10,    // Licenciatura Química

                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'uuid' => Str::uuid(),
                'id_departamento' => 2, // Fisica
                'id_prog_form' => 4,    // Fisica

                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
