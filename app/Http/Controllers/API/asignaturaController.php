<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Disciplina_Asignatura;
use Illuminate\Support\Facades\Validator;

class asignaturaController extends Controller
{
   public function index()
    {
        $asignatura = Asignatura::all();

        return response()->json([
            'res' => true,
            'data' => $asignatura
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

        $asignatura = Asignatura::create([
            'nombre'=> $request->nombre,
            'fondo_tiempo'=>$request->fondo_tiempo,
        ]);

        //  manejar relación con DISCIPLINA
        if ($request->has('id_disciplina')) {

            $disciplina = Disciplina::find($request->id_disciplina);

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

        //  manejar relación con disciplina
        if ($request->has('id_disciplina')) {

            $disciplina = Disciplina::find($request->id_disciplina);

            if (!$disciplina) {
                return response()->json([
                    'res' => false,
                    'message' => 'Disciplina no encontrada'
                ], 400);
            }

            $rel = Disciplina_Asignatura::where('id_asignatura', $asignatura->id)
                ->where('id_disciplina', $disciplina->id)
                ->first();

            if (!$rel) {
                Disciplina_Asignatura::create([
                    'id_asignatura' => $asignatura->id,
                    'id_disciplina' => $disciplina->id
                ]);
            }
        }

        // actualizar campos 
        $data = [];

        
        if($request->has('nombre')) $data['nombre'] = $request->nombre;
        if ($request->has('fondo_tiempo')) $data['fondo_tiempo'] = $request->fondo_tiempo;
    

        $asignatura->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Asignatura actualizada'
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

        $asignatura->delete();

        return response()->json([
            'res' => true,
            'message' => 'Asignatura eliminado'
        ], 200);
    }
}
