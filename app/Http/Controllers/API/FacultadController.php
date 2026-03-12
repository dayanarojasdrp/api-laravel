<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Facultad;
use App\Models\HistorialUniversidadFacultad;
use App\Models\Universidad;
use App\Models\Profesor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FacultadController extends Controller
{
    public function index()
    {
        $fac = Facultad::all();
        return response()->json([
            'res'=> true,
            'data'=> $fac
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre"=> "required",
            "abreviatura" => "required",
            "id_profesor" => "required|exists:profesor,id"
        ]);
        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $fac = Facultad::create([
            'nombre' => $request->nombre,
            'abreviatura' => $request->abreviatura,
            'id_profesor' => $request->id_profesor
        ]);

        if ($request->has('id_universidad')) {
            $uni = Universidad::find($request->id_universidad);
            if (!$uni) {
                return response()->json([
                    'res'=> false,
                    'message'=> 'El id de universidad no existe, se creó la facultad pero no el historial de pertenencia'
                ], 400);
            }
            $cur = Curso::find($request->id_curso);
            if (!$cur) {
                return response()->json([
                    'res'=> false,
                    "message" => 'No hay cursos añadidos, se creó la facultad pero no el historial de pertenencia'
                ], 400);
            }

            HistorialUniversidadFacultad::create([
                'id_universidad' => $uni->id,
                'id_facultad' => $fac->id,
                'id_curso' => $cur->id
            ]);
        }

        return response()->json([
            'res'=> true,
            'message'=> 'Facultad guardada correctamente'
        ], 200);
    }

    public function show(string $id)
    {
        $fac = Facultad::find($id);
        if (!$fac) {
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontró la facultad'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'data'=> $fac
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $fac = Facultad::find($id);
        if (!$fac) {
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontró la facultad'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'unique:facultad,nombre,'.$id,
            'id_profesor' => 'nullable|exists:profesor,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }

        if ($request->has('id_universidad')) {
            $idU = Universidad::find($request->id_universidad);
            if (!$idU) {
                return response()->json([
                    'res'=>false,
                    'message'=> 'No se encontró la universidad'
                ], 400);
            }

            $cur = Curso::find($request->id_curso);
            if (!$cur) {
                return response()->json([
                    'res'=> false,
                    'message'=> 'No hay cursos añadidos'
                ], 400);
            }

            $hist = HistorialUniversidadFacultad::where("id_facultad", $id)
                ->where('id_universidad', $idU->id)
                ->where('id_curso', $cur->id)->first();

            if (!$hist) {
                HistorialUniversidadFacultad::create([
                    'id_universidad' => $idU->id,
                    'id_facultad' => $fac->id,
                    'id_curso' => $cur->id
                ]);
            }
        }

        $data = [];
        if ($request->has('nombre')) $data['nombre'] = $request->nombre;
        if ($request->has('abreviatura')) $data['abreviatura'] = $request->abreviatura;
        if ($request->has('id_profesor')) $data['id_profesor'] = $request->id_profesor;

        $fac->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Facultad actualizada'
        ], 200);
    }

    public function destroy(string $id)
    {
        $fac = Facultad::find($id);
        if (!$fac) {
            return response()->json([
                'res'=>false,
                'message'=>'No se encontró la facultad a eliminar'
            ], 400);
        }

        $fac->delete();

        return response()->json([
            'res'=>true,
            'message'=>'Facultad eliminada satisfactoriamente'
        ], 200);
    }
}
