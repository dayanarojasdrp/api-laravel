<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculo;
use App\Models\PlanEstudio;
use App\Models\PlanEstudio_Curriculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class CurriculoController extends Controller
{
    public function index()
    {
        $curriculo = Curriculo::with(
            'planesEstudio'
        )->get();

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
            foreach($request->id_plan_estudio as $idPlan){

                $plan = PlanEstudio::find($idPlan);

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

        // verificar si existe

        if (!$curriculo) {

            return response()->json([
                'res' => false,
                'message' => 'No se encontró el curriculo'
            ], 400);
        }

        // actualizar campos normales

        $data = [];

        if ($request->has('nombre')) {

            $data['nombre'] = $request->nombre;
        }

        $curriculo->update($data);

        // manejar relación muchos a muchos
        // con plan de estudio

        if ($request->has('id_plan_estudio')) {

            // eliminar relaciones actuales

            PlanEstudio_Curriculo::where(
                'id_curriculo',
                $curriculo->id
            )->delete();

            // crear nuevas relaciones

            foreach($request->id_plan_estudio as $idPlan){

                $plan = PlanEstudio::find($idPlan);

                // validar si existe

                if (!$plan) {

                    return response()->json([
                        'res' => false,
                        'message' => 'Plan no encontrado'
                    ], 400);
                }

                // crear relación

                PlanEstudio_Curriculo::create([

                    'id_curriculo' => $curriculo->id,

                    'id_plan_estudio' => $plan->id
                ]);
            }
        }

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

        try {
            DB::beginTransaction();

            PlanEstudio_Curriculo::where('id_curriculo', $curriculo->id)->delete();
            $curriculo->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'res' => false,
                'message' => 'Error al eliminar el curriculo',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'res' => true,
            'message' => 'Curriculo eliminado'
        ], 200);
    }
}
