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
        DB::table('disciplina_asignatura')->delete();
        DB::table('asignatura')->delete();

        $asignaturas = [
            ['nombre' => 'Matematica I', 'fondo_tiempo' => 96],
            ['nombre' => 'Matematica II', 'fondo_tiempo' => 96],
            ['nombre' => 'Matematica III', 'fondo_tiempo' => 80],
            ['nombre' => 'Matematica Numerica', 'fondo_tiempo' => 48],
            ['nombre' => 'Filosofia', 'fondo_tiempo' => 48],
            ['nombre' => 'Economia Politica', 'fondo_tiempo' => 56],
            ['nombre' => 'Teoria Politica', 'fondo_tiempo' => 48],
            ['nombre' => 'Estudios de Ciencia, Tecnologia y Sociedad', 'fondo_tiempo' => 32],
            ['nombre' => 'Historia de Cuba', 'fondo_tiempo' => 56],
            ['nombre' => 'Defensa y Seguridad Nacional', 'fondo_tiempo' => 68],
            ['nombre' => 'Economia Empresarial', 'fondo_tiempo' => 48],
            ['nombre' => 'Arquitectura de Computadoras', 'fondo_tiempo' => 70],
            ['nombre' => 'Sistemas Operativos', 'fondo_tiempo' => 56],
            ['nombre' => 'Redes de Computadoras', 'fondo_tiempo' => 70],
            ['nombre' => 'Seguridad Informatica', 'fondo_tiempo' => 44],
            ['nombre' => 'Logica Matematica', 'fondo_tiempo' => 64],
            ['nombre' => 'Matematica Computacional', 'fondo_tiempo' => 32],
            ['nombre' => 'Inteligencia Artificial I', 'fondo_tiempo' => 42],
            ['nombre' => 'Inteligencia Artificial II', 'fondo_tiempo' => 64],
            ['nombre' => 'Probabilidades y Estadistica', 'fondo_tiempo' => 48],
            ['nombre' => 'Investigacion de Operaciones', 'fondo_tiempo' => 70],
            ['nombre' => 'Fundamentos de la Informatica', 'fondo_tiempo' => 48],
            ['nombre' => 'Introduccion a la Programacion', 'fondo_tiempo' => 64],
            ['nombre' => 'Diseno y Programacion Orientada a Objetos', 'fondo_tiempo' => 80],
            ['nombre' => 'Estructuras de Datos', 'fondo_tiempo' => 80],
            ['nombre' => 'Bases de Datos', 'fondo_tiempo' => 80],
            ['nombre' => 'Ingenieria de Software I', 'fondo_tiempo' => 70],
            ['nombre' => 'Ingenieria de Software II', 'fondo_tiempo' => 70],
            ['nombre' => 'Programacion Web', 'fondo_tiempo' => 70],
            ['nombre' => 'Seminario Profesional', 'fondo_tiempo' => 42],
            ['nombre' => 'Seminario Profesional 2do ano', 'fondo_tiempo' => 160],
            ['nombre' => 'Seminario Profesional 3er ano', 'fondo_tiempo' => 240],
            ['nombre' => 'Trabajo de Diploma', 'fondo_tiempo' => 600],
            ['nombre' => 'Educacion Fisica I', 'fondo_tiempo' => 28],
            ['nombre' => 'Educacion Fisica II', 'fondo_tiempo' => 28],
            ['nombre' => 'Educacion Fisica III', 'fondo_tiempo' => 28],
            ['nombre' => 'Educacion Fisica IV', 'fondo_tiempo' => 28],
            ['nombre' => 'Modelado y Diseno de Interfaces', 'fondo_tiempo' => 56],
            ['nombre' => 'Fisica', 'fondo_tiempo' => 64],
            ['nombre' => 'Taller de Bases de Datos', 'fondo_tiempo' => 48],
            ['nombre' => 'Desarrollo de Aplicaciones Moviles', 'fondo_tiempo' => 48],
            ['nombre' => 'Bases de Datos para la Toma de Decisiones', 'fondo_tiempo' => 64],
            ['nombre' => 'Gestion de Software', 'fondo_tiempo' => 68],
            ['nombre' => 'Proyecto de Trabajo de Diploma', 'fondo_tiempo' => 150],
            ['nombre' => 'Electiva 1', 'fondo_tiempo' => 50],
            ['nombre' => 'Optativa 1', 'fondo_tiempo' => 68],
            ['nombre' => 'Optativa 2', 'fondo_tiempo' => 64],
            ['nombre' => 'Optativa 3', 'fondo_tiempo' => 64],
            ['nombre' => 'Optativa 4', 'fondo_tiempo' => 68],
        ];

        DB::table('asignatura')->insert(array_map(function ($asignatura) {
            return [
                ...$asignatura,
                'horas_clase' => $asignatura['fondo_tiempo'],
                'horas_practica_laboral' => 0,
            ];
        }, $asignaturas));
    }
}
