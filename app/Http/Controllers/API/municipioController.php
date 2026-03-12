<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Municipio;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class municipioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mun = Municipio::all();
        return response()->json([
            'res'=> true,
            'data'=>$mun,
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:municipio,nombre',
            'id_provincia'=>'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=> false,
                'message'=> $validator->errors(),
                'status'=> 400
            ], 400);
        }
        $pro = Provincia::find($request->id_provincia);
        if(!$pro){
            return response()->json([
                'res'=> false,
                'message'=> 'Error no se encontro la provincia',
                'status'=> 400
            ], 400);
        }
        $mun=Municipio::create([
            'nombre'=>$request->nombre,
            'id_provincia'=>$request->id_provincia
        ]);
        if(!$mun){
            return response()->json([
                'res'=> false,
                'message'=> 'Fallo al crear un municipio',
                'status'=> 400
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Se anadio correctamente el municipio',
            'status'=> 400
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mun = Municipio::find($id);
        if(!$mun){  
            return response()->json([
                'res'=>false,
                'message'=>'Erro no se encontro el municipio',
                'status'=> 400
            ], 400);
        }
        return response()->json([
            'res'=>true,
            'data'=> $mun
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mun = Municipio::find($id);
        if(!$mun){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro el municipio',
                'status'=> 400
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'unique:municipio,nombre,'.$id,
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors(),
                'status'=> 400
            ], 400);
        }
        if($request->has('id_provincia')){
            $idP = Provincia::find($request->id_provincia);
            if(!$idP){
                return response()->json([
                    'res'=>false,
                    'message'=> 'Error no se encontro la provincia',
                    'status'=> 400
                ], 400);
            }
        }
        $mun->update($request->all());
        return response()->json([
            'res' => true,
            'message' => 'Municipio actualizado',
            'status'=> 200
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mun = Municipio::find($id);
        if(!$mun){
            return response()->json([
                'res'=>false,
                'message'=>'No se encontro el municipio a eliminar',
                'status'=> 400
            ], 400);
        }
        $mun->delete();
        return response()->json([
            'res'=>true,
            'message'=>'Municipio eliminado satisfactoriamente',
            'status'=> 200
        ], 200);
    }
}
