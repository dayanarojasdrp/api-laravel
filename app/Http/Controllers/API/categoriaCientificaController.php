<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CatCientifica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class categoriaCientificaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CatCientifica::all();
        return response()->json([
            'res'=> true,
            'catCientifica'=> $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:catCientifica,nombre'
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=> false,
                'message'=> $validator->errors()
            ], 400);
        }
        $pro=CatCientifica::create([
            'nombre'=>$request->nombre
        ]);
        if(!$pro){
            return response()->json([
                'res'=> false,
                'message'=> 'Fallo al crear una categoría científica'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Se anadio correctamente la categoría científica'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pro = CatCientifica::find($id);
        if(!$pro){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro la categoría científica'
            ]);
        }
        return response()->json([
            'res'=> true,
            'catCientifica'=> $pro
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pro = CatCientifica::find($id);
        if(!$pro){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro la categoría científica'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:catCientifica,nombre,'.$id,
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
                'message'=> 'Error al actualizar la categoría científica'
            ], 400);
        }
        return response()->json([
            'res' => true,
            'message' => 'Categoría Científica actualizada'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pro = CatCientifica::find($id);
        if(!$pro){
            return response()->json([
                'res'=>false,
                'message'=> "No se encontro la categoría científica"
            ], 400);
        }
        $pro->delete();
        return response()->json([
            'res'=>true,
            'message'=> 'Se elimino la categoría científica correctamente'
        ], 200);
    }
}
