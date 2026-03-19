<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanEstudio;
use App\Models\ProgFormacion;
use App\Models\PlanEstudioProgForm;
use Illuminate\Support\Facades\Validator;

class PlanEstudioController extends Controller
{
    public function index()
    {
        $planes = PlanEstudio::all();

        return response()->json([
            'res' => true,
            'data' => $planes
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

        $plan = PlanEstudio::create([
            'nombre'=> $request->nombre,
        ]);

        //  manejar relación con programa de formación
        if ($request->has('programa_de_formacion_id')) {

            $prog = ProgFormacion::find($request->programa_de_formacion_id);

            if (!$prog) {
                return response()->json([
                    'res' => false,
                    'message' => 'El programa de formación no existe, se creó el plan pero no la relación'
                ], 400);
            }

            PlanEstudioProgForm::create([
                'plan_estudio_id' => $plan->id,
                'programa_de_formacion_id' => $prog->id
            ]);
        }

        return response()->json([
            'res' => true,
            'message' => 'Plan de estudio creado correctamente'
        ], 200);
    }

    public function show(string $id)
    {
        $plan = PlanEstudio::find($id);

        if (!$plan) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el plan de estudio'
            ], 400);
        }

        return response()->json([
            'res' => true,
            'data' => $plan
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $plan = PlanEstudio::find($id);

        if (!$plan) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el plan de estudio'
            ], 400);
        }

        //  manejar relación con programa
        if ($request->has('programa_de_formacion_id')) {

            $prog = ProgFormacion::find($request->programa_de_formacion_id);

            if (!$prog) {
                return response()->json([
                    'res' => false,
                    'message' => 'Programa no encontrado'
                ], 400);
            }

            $rel = PlanEstudioProgForm::where('plan_estudio_id', $plan->id)
                ->where('programa_de_formacion_id', $prog->id)
                ->first();

            if (!$rel) {
                PlanEstudioProgForm::create([
                    'plan_estudio_id' => $plan->id,
                    'programa_de_formacion_id' => $prog->id
                ]);
            }
        }

        // actualizar campos 
        $data = [];

        
        if($request->has('nombre')) $data['nombre'] = $request->nombre;

        $plan->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Plan de estudio actualizado'
        ], 200);
    }
    
    public function destroy(string $id)
    {
        $plan = PlanEstudio::find($id);

        if (!$plan) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el plan de estudio'
            ], 400);
        }

        $plan->delete();

        return response()->json([
            'res' => true,
            'message' => 'Plan de estudio eliminado'
        ], 200);
    }

}
