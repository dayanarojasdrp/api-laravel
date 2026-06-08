<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AsignaturaAgnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('asignatura_agno')->delete();

        $programaInformatica = DB::table('programa_de_formacion')
            ->where('abreviatura', 'II')
            ->first();

        if (! $programaInformatica) {
            return;
        }

        $anios = DB::table('a_academico')
            ->where('id_prog_form', $programaInformatica->id)
            ->pluck('id', 'identificador');

        $asignaturas = DB::table('asignatura')->pluck('id', 'nombre');

        $relaciones = [
            '1ro' => [
                'Matematica I',
                'Matematica II',
                'Filosofia',
                'Historia de Cuba',
                'Logica Matematica',
                'Matematica Computacional',
                'Fundamentos de la Informatica',
                'Introduccion a la Programacion',
                'Diseno y Programacion Orientada a Objetos',
                'Educacion Fisica I',
                'Educacion Fisica II',
                'Modelado y Diseno de Interfaces',
                'Fisica',
            ],
            '2do' => [
                'Matematica III',
                'Matematica Numerica',
                'Economia Politica',
                'Defensa y Seguridad Nacional',
                'Economia Empresarial',
                'Arquitectura de Computadoras',
                'Sistemas Operativos',
                'Inteligencia Artificial I',
                'Estructuras de Datos',
                'Bases de Datos',
                'Seminario Profesional 2do ano',
                'Educacion Fisica III',
                'Educacion Fisica IV',
                'Electiva 1',
                'Optativa 1',
                'Optativa 2',
            ],
            '3ro' => [
                'Teoria Politica',
                'Redes de Computadoras',
                'Seguridad Informatica',
                'Probabilidades y Estadistica',
                'Investigacion de Operaciones',
                'Ingenieria de Software I',
                'Ingenieria de Software II',
                'Programacion Web',
                'Seminario Profesional 3er ano',
                'Taller de Bases de Datos',
                'Desarrollo de Aplicaciones Moviles',
                'Optativa 3',
            ],
            '4to' => [
                'Estudios de Ciencia, Tecnologia y Sociedad',
                'Inteligencia Artificial II',
                'Seminario Profesional',
                'Trabajo de Diploma',
                'Bases de Datos para la Toma de Decisiones',
                'Gestion de Software',
                'Proyecto de Trabajo de Diploma',
                'Optativa 3',
                'Optativa 4',
            ],
        ];

        $rows = [];

        foreach ($relaciones as $identificadorAnio => $nombresAsignaturas) {
            $idAnioAcademico = $anios[$identificadorAnio] ?? null;

            if (! $idAnioAcademico) {
                continue;
            }

            foreach ($nombresAsignaturas as $nombreAsignatura) {
                $idAsignatura = $asignaturas[$nombreAsignatura] ?? null;

                if (! $idAsignatura) {
                    continue;
                }

                $rows[] = [
                    'id' => (string) Str::uuid(),
                    'id_asignatura' => $idAsignatura,
                    'id_a_academico' => $idAnioAcademico,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($rows)) {
            DB::table('asignatura_agno')->insert($rows);
        }
    }
}
