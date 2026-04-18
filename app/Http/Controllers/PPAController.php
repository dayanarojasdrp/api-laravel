<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPA;
use App\Models\PpaHistorial;
use App\Models\AgnoAcademicoCurso;

class PPAController extends Controller
{
    // 🟢 DESIGNAR
   public function designar(Request $request)
{
    // ✅ VALIDAR PRIMERO
    $valido = AgnoAcademicoCurso::where('id_curso', $request->id_curso)
        ->where('id_a_academico', $request->id_a_academico)
        ->exists();

    if (!$valido) {
        return response()->json([
            'error' => 'El curso no corresponde a ese año académico'
        ], 400);
    }

    // ✅ SI TODO BIEN → CREAR
    $ppa = PPA::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso
    ]);

    // ✅ HISTORIAL
    PpaHistorial::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'accion' => 'designado',
        'fecha_accion' => now()
    ]);

    return response()->json($ppa);
}

    // 🔵 RATIFICAR
    public function ratificar(Request $request)
    {
        PpaHistorial::create([
            'id_profesor' => $request->id_profesor,
            'id_a_academico' => $request->id_a_academico,
            'accion' => 'ratificado',
            'fecha_accion' => now()
        ]);

        return response()->json(['message' => 'Ratificado']);
    }

    // 🔴 DESNOMBRAR
    public function desnombrar(Request $request)
    {
        $ppa = PPA::where('id_profesor', $request->id_profesor)
            ->where('id_a_academico', $request->id_a_academico)
            ->whereNull('finished_at')
            ->first();

        if ($ppa) {
            $ppa->update([
                'finished_at' => now()
            ]);
        }

        PpaHistorial::create([
            'id_profesor' => $request->id_profesor,
            'id_a_academico' => $request->id_a_academico,
            'accion' => 'desnombrado',
            'fecha_accion' => now()
        ]);

        return response()->json(['message' => 'Desnombrado']);
    }
}
