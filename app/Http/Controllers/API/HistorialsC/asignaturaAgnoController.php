<?php

namespace App\Http\Controllers\API\HistorialsC;

use App\Http\Controllers\Controller;
use App\Models\Asignatura_Agno;
use App\Models\Asignatura;
use App\Models\AnoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class asignaturaAgnoController extends Controller
{
    public function index()
    {
        $data = Asignatura_Agno::all();

        return response()->json([
            'res' => true,
            'data' => $data
        ], 200);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_asignatura' => 'required',
            'id_a_academico' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $rel = Asignatura_Agno::where('id_asignatura', $request->id_asignatura)
            ->where('id_a_academico', $request->id_a_academico)
            ->first();

        if (!$rel) {
            return response()->json([
                'res' => false,
                'message' => 'Relación no encontrada'
            ], 404);
        }

        $rel->delete();

        return response()->json([
            'res' => true,
            'message' => 'Relación eliminada correctamente'
        ], 200);
    }
}
