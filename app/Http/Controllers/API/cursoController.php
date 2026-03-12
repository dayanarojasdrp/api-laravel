<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class cursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'res'=> true,
            'data'=> Curso::all(),
            'status'=> 200
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'curso'=> 'required|unique:curso,curso'
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=> false,
                'message' => $validator->errors()
            ], 400);
        }
        $cur = Curso::create($request->all());
        if(!$cur){
            return response()->json([
                'res'=> false,
                'message' => 'Fallo al anadir el curso'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message' => 'Se anadio correctamente el curso'
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cur = Curso::find($id);
        if(!$cur){
            return response()->json([
                'res'=>false,
                'message'=>'No se encontro el curso a eliminar'
            ], 400);
        }
        $cur->delete();
        return response()->json([
            'res'=>true,
            'message'=>'curso eliminado satisfactoriamente'
        ], 200);
    }
}
