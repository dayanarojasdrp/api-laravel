<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AlumnoAyudante;

class AlumnoAyudanteController extends Controller
{
    // 🔹 listar
    public function index()
    {
        return AlumnoAyudante::with('estudiante')->get();
    }

    // =====================================
    // 🔥 DESIGNAR
    // =====================================
    public function designar(Request $request)
    {
        $request->validate([
            'id_estudiante' => 'required|exists:estudiantes,id',
            'nombre_tutor' => 'required|string',
            'etapa' => 'required|string'
        ]);

        // 🔥 cerrar cualquier registro activo
        AlumnoAyudante::where('id_estudiante', $request->id_estudiante)
            ->where('habilitado', true)
            ->update([
                'habilitado' => false,
                'fecha_fin' => now()
            ]);

        // 🔥 crear nuevo
        return AlumnoAyudante::create([
            'id_estudiante' => $request->id_estudiante,
            'nombre_tutor' => $request->nombre_tutor,
            'etapa' => $request->etapa,
            'fecha_inicio' => now(),
            'fecha_fin' => null,
            'habilitado' => true
        ]);
    }

    // =====================================
    // 🔥 RATIFICAR
    // =====================================
    public function ratificar($id)
    {
        $registro = AlumnoAyudante::findOrFail($id);

        if (!$registro->habilitado) {
            return response()->json([
                'error' => 'No se puede ratificar un registro inactivo'
            ], 400);
        }

        // 🔥 opcional: actualizar fecha
        $registro->update([
            'fecha_inicio' => now()
        ]);

        return $registro;
    }

    // =====================================
    // 🔥 DESNOMBRAR
    // =====================================
    public function desnombrar($id)
    {
        $registro = AlumnoAyudante::findOrFail($id);

        if (!$registro->habilitado) {
            return response()->json([
                'error' => 'Ya está desnombrado'
            ], 400);
        }

        $registro->update([
            'habilitado' => false,
            'fecha_fin' => now()
        ]);

        return response()->json(['ok' => true]);
    }

    // =====================================
    // 🔥 ACTUAL POR ESTUDIANTE
    // =====================================
    public function actual($estudianteId)
    {
        return AlumnoAyudante::where('id_estudiante', $estudianteId)
            ->where('habilitado', true)
            ->first();
    }

    // =====================================
    // 🔥 HISTORIAL
    // =====================================
    public function historial($estudianteId)
    {
        return AlumnoAyudante::where('id_estudiante', $estudianteId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }
}
