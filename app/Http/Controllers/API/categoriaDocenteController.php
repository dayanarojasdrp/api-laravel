<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CatDocente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class categoriaDocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CatDocente::all();
        return response()->json([
            'res'=> true,
            'catDocente'=> $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:catDocente,nombre'
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=> false,
                'message'=> $validator->errors()
            ], 400);
        }
        $pro=CatDocente::create([
            'nombre'=>$request->nombre
        ]);
        if(!$pro){
            return response()->json([
                'res'=> false,
                'message'=> 'Fallo al crear una categoría docente'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Se anadio correctamente la categoría docente'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pro = CatDocente::find($id);
        if(!$pro){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro la categoría docente'
            ]);
        }
        return response()->json([
            'res'=> true,
            'catDocente'=> $pro
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pro = CatDocente::find($id);
        if(!$pro){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro la categoría docente'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:catDocente,nombre,'.$id,
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
                'message'=> 'Error al actualizar la categoría docente'
            ], 400);
        }
        return response()->json([
            'res' => true,
            'message' => 'Categoría Docente actualizada'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pro = CatDocente::find($id);
        if(!$pro){
            return response()->json([
                'res'=>false,
                'message'=> "No se encontro la categoría docente"
            ], 400);
        }
        $pro->delete();
        return response()->json([
            'res'=>true,
            'message'=> 'Se elimino la categoría docente correctamente'
        ], 200);
    }
}
