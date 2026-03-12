<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ModalidadCarrera;
class modalidadCarreraController extends Controller
{
    public function index()
    {
        $modC = ModalidadCarrera::all();
        return response()->json([
            'res'=> true,
            'data'=> $modC
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre"=> "required|unique:modalidad-carrera,nombre"
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        $modC = ModalidadCarrera::create ([
            'nombre'=> $request->nombre,
        ]);
        if(!$modC){
            return response()->json([
                'res'=> false,
                'message'=> 'No se pudo crear la modalidad de carrera'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Modalidad de carrera Guardado Correctamente ',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) 
    {
        $modC = ModalidadCarrera::find($id);
        if(!$modC){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro la modalidad de carrera'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'data'=> $modC
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $modC = ModalidadCarrera::find($id);
        if(!$modC){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro la modalidad de carrera'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'unique:modalidad-carrera,nombre,'.$id,
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        $data = [];
        if($request->has('nombre')) $data['nombre'] = $request->nombre;
        $modC->update($data);
        return response()->json([
            'res' => true,
            'message' => 'modalidad de carrera actualizado'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modC = ModalidadCarrera::find($id);
        if(!$modC){
            return response()->json([
                'res'=>false,
                'message'=>'No se encontro la modalidad de carrera a eliminar'
            ], 400);
        }
        $modC->delete();
        return response()->json([
            'res'=>true,
            'message'=>'Modalidad de carrera eliminada satisfactoriamente'
        ], 200);
    }
}
