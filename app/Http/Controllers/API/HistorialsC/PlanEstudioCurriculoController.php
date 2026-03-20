<?php

namespace App\Http\Controllers\API\HistorialsC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanEstudio_Curriculo;
use Illuminate\Support\Facades\Validator;

class PlanEstudioCurriculoController extends Controller
{
     public function index()
    {
        return response()->json([
            'res' => true,
            'data' => PlanEstudio_Curriculo::all()
        ], 200);
    }

    public function destroy(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id_curriculo' => 'required',
            'id_plan_estudio' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'res' => false,
                'message' => $val->errors()
            ], 400);
        }

        $rel = PlanEstudio_Curriculo::where('id_curriculo', $request->id_curriculo)
            ->where('id_plan_estudio', $request->id_plan_estudio)
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
