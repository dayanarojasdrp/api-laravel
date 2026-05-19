<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Curriculo;
use App\Models\Curriculo_Disciplina;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DisciplinaController extends Controller
{
    public function index()
    {
        $disciplina = Disciplina::with(
            'curriculos'
        )->get();

        return response()->json([
            'res' => true,
            'data' => $disciplina
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre"=> "required",
            "fondo_tiempo"=>"required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $disciplina = Disciplina::create([
            'nombre'=> $request->nombre,
            'fondo_tiempo'=>$request->fondo_tiempo,
        ]);

        //  manejar relación con curriculo
        if ($request->has('id_curriculo')) {
            foreach ($request->id_curriculo as $idCurriculo){

                $curriculo = Curriculo::find($idCurriculo);

                if (!$curriculo) {
                    return response()->json([
                        'res' => false,
                        'message' => 'El curriculo no existe pero, se creó el curriculo pero no la relación'
                    ], 400);
                }

                Curriculo_Disciplina::create([
                    'id_disciplina' => $disciplina->id,
                    'id_curriculo' => $curriculo->id
                ]);
            }
        }

        return response()->json([
            'res' => true,
            'message' => 'Disciplina creada correctamente'
        ], 200);
    }

    public function show(string $id)
    {
        $disciplina = Disciplina::find($id);

        if (!$disciplina) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la disciplina'
            ], 400);
        }

        return response()->json([
            'res' => true,
            'data' => $disciplina
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $disciplina = Disciplina::find($id);

        if (!$disciplina) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la disciplina'
            ], 400);
        }

        //  manejar relación con curriculo
        if ($request->has('id_curriculo')) {

            // eliminar relaciones actuales

            Curriculo_Disciplina::where(
                'id_disciplina',
                $disciplina->id
            )->delete();

            // crear nuevas relaciones

            foreach($request->id_curriculo as $idCurriculo){

                $curriculo = Curriculo::find($idCurriculo);

                if (!$curriculo) {

                    return response()->json([
                        'res' => false,
                        'message' => 'Currículo no encontrado'
                    ], 400);
                }

                Curriculo_Disciplina::create([

                    'id_disciplina' => $disciplina->id,

                    'id_curriculo' => $curriculo->id
                ]);
            }
        }

        // actualizar campos 
        $data = [];

        
        if($request->has('nombre')) $data['nombre'] = $request->nombre;
        if ($request->has('fondo_tiempo')) $data['fondo_tiempo'] = $request->fondo_tiempo;
    

        $disciplina->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Disciplina actualizada'
        ], 200);
    }

    public function destroy(string $id)
    {
        $disciplina = Disciplina::find($id);

        if (!$disciplina) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la disciplina'
            ], 400);
        }

        try {
            DB::beginTransaction();

            Curriculo_Disciplina::where('id_disciplina', $disciplina->id)->delete();
            $disciplina->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'res' => false,
                'message' => 'Error al eliminar la disciplina',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'res' => true,
            'message' => 'Disciplina eliminado'
        ], 200);
    }
}
