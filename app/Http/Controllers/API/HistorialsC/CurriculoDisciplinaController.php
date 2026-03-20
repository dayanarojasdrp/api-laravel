<?php

namespace App\Http\Controllers\API\HistorialsC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculo_Disciplina;
use Illuminate\Support\Facades\Validator;

class CurriculoDisciplinaController extends Controller
{
    public function index()
    {
        return response()->json([
            'res' => true,
            'data' => Curriculo_Disciplina::all()
        ], 200);
    }

    public function destroy(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id_disciplina' => 'required',
            'id_curriculo' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'res' => false,
                'message' => $val->errors()
            ], 400);
        }

        $rel = Curriculo_Disciplina::where('id_disciplina', $request->id_disciplina)
            ->where('id_curriculo', $request->id_curriculo)
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
