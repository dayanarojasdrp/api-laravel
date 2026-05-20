<?php

namespace Database\Seeders;

use App\Models\CatCientifica;
use App\Models\Documento;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Documento::truncate();
        Storage::disk('public')->deleteDirectory('documentos');
        Storage::disk('public')->makeDirectory('documentos');

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
         $this->call([
        ProgFormSeeder::class,
        AgnoAcademicoSeeder::class,
        ProvinciaSeeder::class,
        AsignaturaSeeder::class,
        CursoSeeder::class,
        AsignaturaAgno::class,
        MunicipioSeeder::class,
        CatDocenteSeeder::class,
        CatCientificaSeeder::class,
        PlanEstudioSeeder::class,
        VersionSeeder::class,
        CohorteSeeder::class,
        UserAppAccessSeeder::class,
        FacultadSeeder::class,
        DepartamentoSeeder::class,
        FacultadDepartamentoSeeder::class,
        AgnoAcademicoCursoSeeder::class,
        DepartamentoProgFormSeeder::class,
        EstudianteSeeder::class,
        TipoSeeder::class,
        EdicionSeeder::class,
        ManifestacionSeeder::class,
        EdicionCursoSeeder::class,
        EstudianteManifestacionSeeder::class,
        GrupoSeeder::class,
        AnoGrupoSeeder::class,
        EstudianteGrupoSeeder::class,
        SectorEstrategicoSeeder::class,
        TDPPSeeder::class,
        EstudianteTDPPSeeder::class,
        GradoTituloSeeder::class,
        ProfesorSeeder::class,
        MiembroDepartamentoSeeder::class,
        DecanoSeeder::class,
        JefeDepartamentoSeeder::class,
        ProfesorGuiaSeeder::class,
        PlanEstudioSeeder::class,
        PlanEstudioProgFormSeeder::class,
        CurriculoSeeder::class,
        DisciplinaSeeder::class,
        DisciplinaAsignaturaSeeder::class,
        AsignaturaAgnoSeeder::class,

    ]);
    }
}
