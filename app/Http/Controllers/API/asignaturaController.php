<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Disciplina_Asignatura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Asignatura_Agno;
use App\Models\AnoAcademico;

class asignaturaController extends Controller
{
   public function index()
    {
        $asignaturas = Asignatura::with([
            'disciplinas',
            'aniosAcademicos.programaFormacion'
        ])->get();

        return response()->json([
            'res' => true,
            'data' => $asignaturas
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre"=> "required",
            "fondo_tiempo"=>"required",
            "id_a_academico"=>"required|array",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $asignatura = Asignatura::create([
            'nombre'=> $request->nombre,
            'fondo_tiempo'=>$request->fondo_tiempo,
        ]);

        //  manejar relación con DISCIPLINA
        if ($request->has('id_disciplina')) {
            foreach($request->id_disciplina as $idDisciplina){

                $disciplina = Disciplina::find($idDisciplina);

                if (!$disciplina) {
                    return response()->json([
                        'res' => false,
                        'message' => 'La disciplina no existe pero, se creó la asignatura pero no la relación'
                    ], 400);
                }

                Disciplina_Asignatura::create([
                    'id_asignatura' => $asignatura->id,
                    'id_disciplina' => $disciplina->id
                ]);
            }
        }
        //Manejar relacion con año academico
        if ($request->has('id_a_academico')) {

            foreach($request->id_a_academico as $idAnioAcademico){

                $anioAcademico = AnoAcademico::find(
                    $idAnioAcademico
                );

                if (!$anioAcademico) {

                    return response()->json([
                        'res' => false,
                        'message' => 'El año académico no existe pero, se creó la asignatura pero no la relación'
                    ], 400);
                }

                Asignatura_Agno::create([
                    'id_asignatura' => $asignatura->id,
                    'id_a_academico' => $anioAcademico->id
                ]);
            }
        }

        return response()->json([
            'res' => true,
            'message' => 'Asignatura creada correctamente'
        ], 200);
    }

    public function show(string $id)
    {
        $asignatura = Asignatura::find($id);

        if (!$asignatura) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la asignatura'
            ], 400);
        }

        return response()->json([
            'res' => true,
            'data' => $asignatura
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $asignatura = Asignatura::find($id);

        if (!$asignatura) {

            return response()->json([
                'res' => false,
                'message' => 'No se encontró la asignatura'
            ], 400);
        }

        // actualizar datos básicos

        $data = [];

        if ($request->has('nombre')) {
            $data['nombre'] = $request->nombre;
        }

        if ($request->has('fondo_tiempo')) {
            $data['fondo_tiempo'] = $request->fondo_tiempo;
        }

        $asignatura->update($data);

       
        // DISCIPLINAS
       

        if ($request->has('id_disciplina')) {

            Disciplina_Asignatura::where(
                'id_asignatura',
                $asignatura->id
            )->delete();

            foreach($request->id_disciplina as $idDisciplina){

                $disciplina = Disciplina::find($idDisciplina);

                if (!$disciplina) {

                    return response()->json([
                        'res' => false,
                        'message' => 'Disciplina no encontrada'
                    ], 400);
                }

                Disciplina_Asignatura::create([

                    'id_asignatura' => $asignatura->id,

                    'id_disciplina' => $disciplina->id
                ]);
            }
        }

        
        // AÑOS ACADÉMICOS
        

        if ($request->has('id_a_academico')) {

            Asignatura_Agno::where(
                'id_asignatura',
                $asignatura->id
            )->delete();

            foreach($request->id_a_academico as $idAno){

                $ano = AnoAcademico::find($idAno);

                if (!$ano) {

                    return response()->json([
                        'res' => false,
                        'message' => 'Año académico no encontrado'
                    ], 400);
                }

                Asignatura_Agno::create([

                    'id_asignatura' => $asignatura->id,

                    'id_a_academico' => $ano->id
                ]);
            }
        }

        return response()->json([
            'res' => true,
            'message' => 'Asignatura actualizada correctamente'
        ], 200);
       
    }

    public function destroy(string $id)
    {
        $asignatura = Asignatura::find($id);

        if (!$asignatura) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la Asignatura'
            ], 400);
        }

        try {
            DB::beginTransaction();

            Disciplina_Asignatura::where('id_asignatura', $asignatura->id)->delete();
            Asignatura_Agno::where('id_asignatura', $asignatura->id)->delete();

            $asignatura->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'res' => false,
                'message' => 'Error al eliminar la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'res' => true,
            'message' => 'Asignatura eliminado'
        ], 200);
    }
}
