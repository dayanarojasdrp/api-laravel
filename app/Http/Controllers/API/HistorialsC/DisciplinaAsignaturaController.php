<?php

namespace App\Http\Controllers\API\HistorialsC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina_Asignatura;
use Illuminate\Support\Facades\Validator;

class DisciplinaAsignaturaController extends Controller
{
    public function index()
    {
        return response()->json([
            'res' => true,
            'data' => Disciplina_Asignatura::all()
        ], 200);
    }

     public function destroy(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id_asignatura' => 'required',
            'id_disciplina' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'res' => false,
                'message' => $val->errors()
            ], 400);
        }

        $rel = Disciplina_Asignatura::where('id_asignatura', $request->id_asignatura)
            ->where('id_disciplina', $request->id_disciplina)
            ->first();

        if (!$rel) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la relación'
            ], 400);
        }

        $rel->delete();

        return response()->json([
            'res' => true,
            'message' => 'Relación eliminada correctamente'
        ], 200);
    }
}
