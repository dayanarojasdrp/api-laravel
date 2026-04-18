<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPA;
use App\Models\PpaHistorial;
use App\Models\AgnoAcademico_Curso;
use App\Models\AñoAcademico;
use App\Models\Profesor;
use App\Models\Curso;

class PPAController extends Controller
{
    // 🟢 DESIGNAR
  public function designar(Request $request)
{
    // 🟡 VALIDAR PROFESOR
    if (!Profesor::where('id', $request->id_profesor)->exists()) {
        return response()->json([
            'error' => 'El profesor no existe'
        ], 400);
    }
    $existe = PPA::where('id_profesor', $request->id_profesor)
    ->where('id_curso', $request->id_curso)
    ->where('id_a_academico', $request->id_a_academico)
    ->whereNull('finished_at') // importante si usas historial de vida
    ->exists();

if ($existe) {
    return response()->json([
        'error' => 'El profesor ya está designado como PPA en ese curso y año académico'
    ], 400);
}
    // 🟡 VALIDAR CURSO
    if (!Curso::where('id', $request->id_curso)->exists()) {
        return response()->json([
            'error' => 'El curso no existe'
        ], 400);
    }

    // 🟡 VALIDAR AÑO ACADÉMICO
    if (!AñoAcademico::where('id', $request->id_a_academico)->exists()) {
        return response()->json([
            'error' => 'El año académico no existe'
        ], 400);
    }

    // 🟢 VALIDACIÓN PRINCIPAL (curso pertenece al año)
    $valido = AgnoAcademico_Curso::where('id_curso', $request->id_curso)
        ->where('id_a_academico', $request->id_a_academico)
        ->exists();

    if (!$valido) {
        return response()->json([
            'error' => 'El curso no corresponde a ese año académico'
        ], 400);
    }

    // ✅ CREAR PPA
    $ppa = PPA::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso
    ]);

    // ✅ HISTORIAL
    PpaHistorial::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso,
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
            'id_curso' => $request->id_curso,
            'accion' => 'ratificado',
            'fecha_accion' => now()
        ]);

        return response()->json(['message' => 'Ratificado']);
    }

    // 🔴 DESNOMBRAR
    public function desnombrar(Request $request)
{
    $ppa = PPA::where('id_profesor', $request->id_profesor)
        ->where('id_curso', $request->id_curso)
        ->where('id_a_academico', $request->id_a_academico)
        ->first();

    if (!$ppa) {
        return response()->json([
            'error' => 'No existe PPA activo'
        ], 404);
    }

    // 🗑️ ELIMINAR de tabla actual
    $ppa->delete();

    // 📝 GUARDAR HISTORIAL
    PpaHistorial::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso,
        'accion' => 'desnombrado',
        'fecha_accion' => now()
    ]);

    return response()->json([
        'message' => 'PPA eliminado correctamente'
    ]);
}
}
