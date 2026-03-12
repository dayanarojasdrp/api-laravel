<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatCientificaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
     {
        DB::table('categoria_cientifica')->insert([
                [
                    "nombre"=> "Investigador Agregado"
                ],[
                    "nombre"=> "Investigador Auxiliar"
                ],[
                    "nombre"=> "Investigador Titular"
                ]
            ]);
    }
}
