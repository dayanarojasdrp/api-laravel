<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanEstudioProgForm;
use Illuminate\Support\Facades\Validator;

class PlanEstudioProgFormController extends Controller
{
     public function index()
    {
        return response()->json([
            'res' => true,
            'data' => PlanEstudioProgForm::all()
        ], 200);
    }

    public function destroy(Request $request)
    {
        $val = Validator::make($request->all(), [
            'plan_estudio_id' => 'required',
            'programa_de_formacion_id' => 'required'
        ]);

        if ($val->fails()) {
            return response()->json([
                'res' => false,
                'message' => $val->errors()
            ], 400);
        }

        $rel = PlanEstudioProgForm::where('plan_estudio_id', $request->plan_estudio_id)
            ->where('programa_de_formacion_id', $request->programa_de_formacion_id)
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
