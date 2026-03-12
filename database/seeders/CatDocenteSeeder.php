<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatDocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categoria_docente')->insert([
                [
                    "nombre"=> "Instructor"
                ],[
                    "nombre"=> "Asistente"
                ],[
                    "nombre"=> "Auxiliar"
                ],[
                    "nombre"=> "Titular"
                ],[
                    "nombre"=> "Profesor Consultante"
                ]
            ]);
    }
}
