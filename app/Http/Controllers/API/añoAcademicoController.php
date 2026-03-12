<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgFormacion;
use App\Models\AñoAcademico;
use Illuminate\Support\Facades\Validator;

class añoAcademicoController extends Controller
{
    public function index()
    {
        $aA = AñoAcademico::all();
        return response()->json([
            'res'=> true,
            'data'=> $aA
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "identificador"=> "required",
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        $data = ["identificador"=>$request->identificador];
        if($request->has("id_prog_form")){
            $pF = ProgFormacion::find($request->id_prog_form);
            if(!$pF){
                return response()->json([
                    'res'=> false,
                    'message'=> 'El id de el programa de formacion no existe'
                ], 400);
            }
            $data['id_prog_form'] = $request->id_prog_form;
        }
        $aA = AñoAcademico::create ($data);
        if(!$aA) {
            return response()->json([
                'res'=> false,
                'message'=> 'No se pudo crear el registro'
            ]);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Año academico Guardado Correctamente ',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) 
    {
        $aA = AñoAcademico::find($id);
        if(!$aA){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro el año academico'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'data'=> $aA
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $aA = AñoAcademico::find($id);
        if(!$aA){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro el año academico'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'unique:a-academico,nombre,'.$id,
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        if($request->has('id_prog_form')){
            $pF = ProgFormacion::find($request->id_prog_form);
            if(!$pF){
                return response()->json([
                    'res'=>false,
                    'message'=> 'No se encontro el programa de formacion'
                ], 400);
            }
        }
        $data = [];
        if($request->has('identificador')) $data['identificador'] = $request->identificador;
        if($request->has('id_prog_form')) $data['id_prog_form'] = $request->id_prog_form;
        $aA->update($data);
        return response()->json([
            'res' => true,
            'message' => 'año academico actualizado'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aA = AñoAcademico::find($id);
        if(!$aA){
            return response()->json([
                'res'=>false,
                'message'=>'No se encontro el año academico a eliminar'
            ], 400);
        }
        $aA->delete();
        return response()->json([
            'res'=>true,
            'message'=>'Año academico eliminado satisfactoriamente'
        ], 200);
    }
}
