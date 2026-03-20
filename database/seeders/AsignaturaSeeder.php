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
                'nombre' => 'Matematica 1',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Introduccion a la Programacion',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Programacion Orientada a Objetos',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Arquitectura de Computadoras',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Historia',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Filosofia',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Economia Politica',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Educacion Fisica 1',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Estructura de Datos',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Base de datos',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Ingenieria de Software 1',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Inteligencia Artificial 1',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Matematica Computacional',
                'fondo_tiempo'=> 60
            ],[
                'nombre' => 'Fisica',
                'fondo_tiempo'=> 60
            ]
        ]);
    }
}
