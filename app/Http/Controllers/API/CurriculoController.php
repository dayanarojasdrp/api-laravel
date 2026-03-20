<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculo;
use App\Models\PlanEstudio;
use App\Models\PlanEstudio_Curriculo;
use Illuminate\Support\Facades\Validator;
class CurriculoController extends Controller
{
    public function index()
    {
        $curriculo = Curriculo::all();

        return response()->json([
            'res' => true,
            'data' => $curriculo
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre"=> "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $curriculo = Curriculo::create([
            'nombre'=> $request->nombre,
        ]);

        //  manejar relación con plan de estudio
        if ($request->has('id_plan_estudio')) {

            $plan = PlanEstudio::find($request->id_plan_estudio);

            if (!$plan) {
                return response()->json([
                    'res' => false,
                    'message' => 'El plan de estudio no existe, se creó el curriculo pero no la relación'
                ], 400);
            }

            PlanEstudio_Curriculo::create([
                'id_curriculo' => $curriculo->id,
                'id_plan_estudio' => $plan->id
            ]);
        }

        return response()->json([
            'res' => true,
            'message' => 'Curriculo creado correctamente'
        ], 200);
    }

    public function show(string $id)
    {
        $curriculo = Curriculo::find($id);

        if (!$curriculo) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el curriculo'
            ], 400);
        }

        return response()->json([
            'res' => true,
            'data' => $curriculo
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $curriculo = Curriculo::find($id);

        if (!$curriculo) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el curriculo'
            ], 400);
        }

        //  manejar relación con plan de estudio
        if ($request->has('id_plan_estudio')) {

            $plan = PlanEstudio::find($request->id_plan_estudio);

            if (!$plan) {
                return response()->json([
                    'res' => false,
                    'message' => 'Plan no encontrado'
                ], 400);
            }

            $rel = PlanEstudio_Curriculo::where('id_curriculo', $curriculo->id)
                ->where('id_plan_estudio', $plan->id)
                ->first();

            if (!$rel) {
                PlanEstudio_Curriculo::create([
                    'id_curriculo' => $curriculo->id,
                    'id_plan_estudio' => $plan->id
                ]);
            }
        }

        // actualizar campos 
        $data = [];

        
        if($request->has('nombre')) $data['nombre'] = $request->nombre;

        $curriculo->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Curriculo actualizado'
        ], 200);
    }
    
    public function destroy(string $id)
    {
        $curriculo = Curriculo::find($id);

        if (!$curriculo) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el curriculo'
            ], 400);
        }

        $curriculo->delete();

        return response()->json([
            'res' => true,
            'message' => 'Curriculo eliminado'
        ], 200);
    }
}
