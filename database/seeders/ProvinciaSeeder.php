<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provincia')->insert([
            [
                "nombre"=> "Pinar del Rio"
            ],[
                "nombre"=> "Mayabeque"
            ],[
                "nombre"=> "La Habana"
            ],[
                "nombre"=> "Artemisa"
            ],[
                "nombre"=> "Matanzas"
            ],[
                "nombre"=> "Villa Clara"
            ],[
                "nombre"=> "Cienfuegos"
            ],[
                "nombre"=> "Sancti Spiritus"
            ],[
                "nombre"=> "Ciego de Avila"
            ],[
                "nombre"=> "Camaguey"
            ],[
                "nombre"=> "Las Tunas"
            ],[
                "nombre"=> "Holguin"
            ],[
                "nombre"=> "Granma"
            ],[
                "nombre"=> "Santiago de Cuba"
            ],[
                "nombre"=> "Guantanamo"
            ]
        ]);
    }
}
