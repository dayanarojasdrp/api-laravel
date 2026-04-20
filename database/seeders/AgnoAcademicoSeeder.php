<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgnoAcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('a_academico')->insert([
            [
                'identificador' => '1ro',
                'id_prog_form' => 1
            ],[
                'identificador' => '2do',
                'id_prog_form' => 1
            ],[
                'identificador' => '3ro',
                'id_prog_form' => 1
            ],[
                'identificador' => '4to',
                'id_prog_form' => 1
            ],[
                'identificador' => '1ro',
                'id_prog_form' => 5
            ],[
                'identificador' => '2do',
                'id_prog_form' => 5
            ],[
                'identificador' => '3ro',
                'id_prog_form' => 5
            ],[
                'identificador' => '4to',
                'id_prog_form' => 5
            ],[
                'identificador' => '1ro',
                'id_prog_form' => 2
            ],[
                'identificador' => '2do',
                'id_prog_form' => 2
            ],[
                'identificador' => '3ro',
                'id_prog_form' => 2
            ],[
                'identificador' => '4to',
                'id_prog_form' => 2
            ]
        ]);
    }
}
