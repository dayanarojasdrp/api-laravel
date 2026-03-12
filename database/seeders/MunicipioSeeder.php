<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('municipio')->insert([
            [
                "nombre"=>"Pinar del Rio",
                "id_provincia"=> 1
            ],[
                "nombre"=>"Centro Habana",
                "id_provincia"=> 3
            ],[
                "nombre"=>"Matanzas",
                "id_provincia"=> 5
            ],[
                "nombre"=>"Santa Clara",
                "id_provincia"=> 6
            ],[
                "nombre"=>"Cienfuegos",
                "id_provincia"=> 7
            ],[
                "nombre"=>"Sancti Spiritus",
                "id_provincia"=> 8
            ],[
                "nombre"=>"Ciego de Avila",
                "id_provincia"=> 9  
            ],[
                "nombre"=>"Camaguey",
                "id_provincia"=> 10
            ]
        ]);
    }
}
