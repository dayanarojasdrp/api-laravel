<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class provinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Provincia::all();
        return response()->json([
            'res'=> true,
            'provincia'=> $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:provincia,nombre'
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=> false,
                'message'=> $validator->errors()
            ], 400);
        }
        $pro=Provincia::create([
            'nombre'=>$request->nombre
        ]);
        if(!$pro){
            return response()->json([
                'res'=> false,
                'message'=> 'Fallo al crear una provincia'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Se anadio correctamente la provincia'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pro = Provincia::find($id);
        if(!$pro){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro la provincia'
            ]);
        }
        return response()->json([
            'res'=> true,
            'provincia'=> $pro
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pro = Provincia::find($id);
        if(!$pro){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro la provincia'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:provincia,nombre,'.$id,
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        $pro->update($request->all());
        if(!$pro){
            return response()->json([
                'res'=>false,
                'message'=> 'Error al actualizar la provincia'
            ], 400);
        }
        return response()->json([
            'res' => true,
            'message' => 'Provincia actualizada'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pro = Provincia::find($id);
        if(!$pro){
            return response()->json([
                'res'=>false,
                'message'=> "No se encontro la provincia"
            ], 400);
        }
        $pro->delete();
        return response()->json([
            'res'=>true,
            'message'=> 'Se elimino la provincia correctamente'
        ], 200);
    }
}
