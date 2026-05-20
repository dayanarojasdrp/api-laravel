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
        DB::table('asignatura_agno')->insert([


            ['id' => Str::uuid(), 'id_asignatura' => 1, 'id_a_academico' => 1, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 2, 'id_a_academico' => 1, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 5, 'id_a_academico' => 1, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 8, 'id_a_academico' => 1, 'created_at'=>now(), 'updated_at'=>now(),],


            ['id' => Str::uuid(), 'id_asignatura' => 3, 'id_a_academico' => 2, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 9, 'id_a_academico' => 2, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 13, 'id_a_academico' => 2, 'created_at'=>now(), 'updated_at'=>now(),],


            ['id' => Str::uuid(), 'id_asignatura' => 10, 'id_a_academico' => 3, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 11, 'id_a_academico' => 3, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 6, 'id_a_academico' => 3, 'created_at'=>now(), 'updated_at'=>now(),],


            ['id' => Str::uuid(), 'id_asignatura' => 12, 'id_a_academico' => 4, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 4, 'id_a_academico' => 4, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 7, 'id_a_academico' => 4, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 14, 'id_a_academico' => 4, 'created_at'=>now(), 'updated_at'=>now(),],




            ['id' => Str::uuid(), 'id_asignatura' => 1, 'id_a_academico' => 5, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 2, 'id_a_academico' => 5, 'created_at'=>now(), 'updated_at'=>now(),],

            ['id' => Str::uuid(), 'id_asignatura' => 3, 'id_a_academico' => 1, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 9, 'id_a_academico' => 1, 'created_at'=>now(), 'updated_at'=>now(),],

            ['id' => Str::uuid(), 'id_asignatura' => 10, 'id_a_academico' => 2, 'created_at'=>now(), 'updated_at'=>now(),],

            ['id' => Str::uuid(), 'id_asignatura' => 12, 'id_a_academico' => 3, 'created_at'=>now(), 'updated_at'=>now(),],




            ['id' => Str::uuid(), 'id_asignatura' => 1, 'id_a_academico' => 4, 'created_at'=>now(), 'updated_at'=>now(),],
            ['id' => Str::uuid(), 'id_asignatura' => 5, 'id_a_academico' => 4, 'created_at'=>now(), 'updated_at'=>now(),],

            ['id' => Str::uuid(), 'id_asignatura' => 3, 'id_a_academico' => 5, 'created_at'=>now(), 'updated_at'=>now(),],

            ['id' => Str::uuid(), 'id_asignatura' => 10, 'id_a_academico' => 1, 'created_at'=>now(), 'updated_at'=>now(),],

            ['id' => Str::uuid(), 'id_asignatura' => 12, 'id_a_academico' => 2, 'created_at'=>now(), 'updated_at'=>now(),],
        ]);
    }
}
