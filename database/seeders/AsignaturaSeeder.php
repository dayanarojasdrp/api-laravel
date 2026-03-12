<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('asignatura')->insert([
            [
                'nombre' => 'Matematica 1'
            ],[
                'nombre' => 'Introduccion a la Programacion'
            ],[
                'nombre' => 'Programacion Orientada a Objetos'
            ],[
                'nombre' => 'Arquitectura de Computadoras'
            ],[
                'nombre' => 'Historia'
            ],[
                'nombre' => 'Filosofia'
            ],[
                'nombre' => 'Economia Politica'
            ],[
                'nombre' => 'Educacion Fisica 1'
            ],[
                'nombre' => 'Estructura de Datos'
            ],[
                'nombre' => 'Base de datos'
            ],[
                'nombre' => 'Ingenieria de Software 1'
            ],[
                'nombre' => 'Inteligencia Artificial 1'
            ],[
                'nombre' => 'Matematica Computacional'
            ],[
                'nombre' => 'Fisica'
            ]
        ]);
    }
}
