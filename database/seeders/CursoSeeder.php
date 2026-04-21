<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Curso')->insert([
            [
                "curso"=>"2000"
            ],[
                "curso"=>"2001"
            ],[
                "curso"=>"2002"
            ],[
                "curso"=>"2003"
            ],[
                "curso"=>"2004"
            ],[
                "curso"=>"2005"
            ],[
                "curso"=>"2006"
            ],[
                "curso"=>"2007"
            ],[
                "curso"=>"2008"
            ],[
                "curso"=>"2009"
            ],[
                "curso"=>"2010"
            ],[
                "curso"=>"2011"
            ],[
                "curso"=>"2012"
            ],[
                "curso"=>"2013"
            ],[
                "curso"=>"2014"
            ],[
                "curso"=>"2015"
            ],[
                "curso"=>"2016"
            ],[
                'curso'=> '2017'
            ],[
                'curso'=> '2018'
            ],[
                'curso'=> '2019'
            ],[
                'curso'=> '2020'
            ],[
                'curso'=> '2021'
            ],[
                'curso'=> '2022'
            ],[
                'curso'=> '2023'
            ],[
                'curso'=> '2024'
            ],[
                'curso'=> '2025'
            ],[
                'curso'=> '2026'
            ]
        ]);
    }
}
