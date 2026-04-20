<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('programa_de_formacion')->insert([
            [
                'nombre' => 'Ing. Informatica',
                'abreviatura' => 'II'
            ],[
                'nombre' => 'Lic. Ciencia de la Computacion',
                'abreviatura' => 'CC'
            ],[
                'nombre' => 'Lic. Ciencia de la Informacion',
                'abreviatura' => 'CI'
            ],[
                'nombre' => 'Lic. Fisica',
                'abreviatura' => 'F'
            ],[
                'nombre' => 'Lic. Matematica',
                'abreviatura' => 'M'
            ],[
                'nombre' => 'Lic. Derecho',
                'abreviatura' => 'D'
            ],[
                'nombre' => 'Lic. Sociologia',
                'abreviatura' => 'S'
            ],[
                'nombre' => 'Ing. Mecanica',
                'abreviatura' => 'Ing.M'
            ],[
                'nombre' => 'Ing. Industrial',
                'abreviatura' => 'Ing.I'
            ],[
                'nombre' => 'Lic. Quimica',
                'abreviatura' => 'LQ'
            ],
        ]);
    }
}
