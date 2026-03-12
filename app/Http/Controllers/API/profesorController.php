<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Models\CatDocente;
use App\Models\CatCientifica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfesorController extends Controller
{
    /**
     * Listar todos los profesores.
     */
    public function index()
    {
        return response()->json([
            'res' => true,
            'data' => Profesor::all()
        ], 200);
    }

    /**
     * Guardar un nuevo profesor.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:50',
            'apellidos' => 'required|max:50',
            'idCatDocente' => 'required',
            'idCatCientifica' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }


        $idCC = CatCientifica::find($request->idCatCientifica);
        if(!$idCC){
            return response()->json([
                'res'=>false,
                'message'=> 'Error el id de la categoría científica no existe'
            ], 400);
        }
        $idCD = CatDocente::find($request->idCatDocente);
        if(!$idCD){
            return response()->json([
                'res'=>false,
                'message'=> 'Error el id de la categoría docente no existe'
            ], 400);
        }
        $prof = Profesor::create([
            'nombre' => $request->nombre,
            'apellidos'=>$request->apellidos,
            'idCatDocente'=>$idCD->id,
            'idCatCientifica'=> $idCC->id
        ]);
        return response()->json([
            'res'=> true,
            'message'=> 'Se añadió el profesor correctamente'
        ], 200);
    }

    /**
     * Mostrar un profesor específico.
     */
    public function show(string $id)
    {
        $prof = Profesor::find($id);
        if (!$prof) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el profesor'
            ], 404);
        }

        return response()->json([
            'res' => true,
            'data' => $prof
        ], 200);
    }

    /**
     * Actualizar un profesor.
     */
    public function update(Request $request, string $id)
    {
        $prof = Profesor::find($id);
        if (!$prof) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el profesor'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'idCatDocente' => 'exists:categoria_docente,id',
            'idCatCientifica' => 'exists:categoria_cientifica,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }
        if($request->has('idCatDocente')){
            $idCD = CatDocente::find($request->idCatDocente);
            if(!$idCD){
                return response()->json([
                    'res'=>false,
                    'message'=> 'Error no se encontro la categoría docente'
                ], 400);
            }
            $prof->idCatDocente = $idCD->idCatDocente;
        }
        if($request->has('idCatCientifica')){
            $idCC = CatCientifica::find($request->idCatCientifica);
            if(!$idCC){
                return response()->json([
                    'res'=>false,
                    'message'=> 'Error no se encontro la categoría científica'
                ], 400);
            }
            $prof->idCatCientifica = $idCC->idCatCientifica;
        }
        $prof->update($request->all());

        return response()->json([
            'res' => true,
            'message' => 'Profesor actualizado correctamente'
        ], 200);
    }

    /**
     * Eliminar un profesor.
     */
    public function destroy(string $id)
    {
        $prof = Profesor::find($id);
        if (!$prof) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el profesor a eliminar'
            ], 404);
        }

        $prof->delete();

        return response()->json([
            'res' => true,
            'message' => 'Profesor eliminado correctamente'
        ], 200);
    }
}
