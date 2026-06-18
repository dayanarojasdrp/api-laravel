<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Curriculo;
use App\Models\Curriculo_Disciplina;
use App\Models\Disciplina_Asignatura;
use App\Models\Asignatura;
use App\Models\Asignatura_Agno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DisciplinaController extends Controller
{
    public function index()
    {
        $disciplina = Disciplina::with([
            'curriculos',
            'asignaturas',
        ])->get()->map(function ($disciplina) {
            $disciplina->fondo_tiempo = $disciplina->asignaturas->sum('fondo_tiempo');
            $disciplina->horas_clase = $disciplina->asignaturas->sum('horas_clase');
            $disciplina->horas_practica_laboral = $disciplina->asignaturas->sum('horas_practica_laboral');

            return $disciplina;
        });

        return response()->json([
            'res' => true,
            'data' => $disciplina
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre"=> "required",
            "id_prog_form" => "nullable|exists:programa_de_formacion,id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $disciplina = Disciplina::create([
            'nombre'=> $request->nombre,
            'fondo_tiempo'=> 0,
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
                    'id_curriculo' => $curriculo->id,
                    'id_prog_form' => $request->id_prog_form ?: null,
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
        $disciplina = Disciplina::with(['curriculos', 'asignaturas'])->find($id);

        if (!$disciplina) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la disciplina'
            ], 400);
        }

        $disciplina->fondo_tiempo = $disciplina->asignaturas->sum('fondo_tiempo');
        $disciplina->horas_clase = $disciplina->asignaturas->sum('horas_clase');
        $disciplina->horas_practica_laboral = $disciplina->asignaturas->sum('horas_practica_laboral');

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

                    'id_curriculo' => $curriculo->id,

                    'id_prog_form' => $request->id_prog_form ?: null,
                ]);
            }
        }

        // actualizar campos 
        $data = [];

        
        if($request->has('nombre')) $data['nombre'] = $request->nombre;
    

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

            $asignaturaIds = Disciplina_Asignatura::where(
                'id_disciplina',
                $disciplina->id
            )->pluck('id_asignatura');

            foreach ($asignaturaIds as $asignaturaId) {
                $relaciones = Disciplina_Asignatura::where(
                    'id_asignatura',
                    $asignaturaId
                )->count();

                Disciplina_Asignatura::where('id_disciplina', $disciplina->id)
                    ->where('id_asignatura', $asignaturaId)
                    ->delete();

                if ($relaciones <= 1) {
                    Asignatura_Agno::where('id_asignatura', $asignaturaId)->delete();
                    Asignatura::where('id', $asignaturaId)->delete();
                }
            }

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
