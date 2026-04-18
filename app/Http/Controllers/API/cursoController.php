<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Version;
use App\Models\Cohorte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AgnoAcademico_Curso;

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
            'curso'=> 'required|unique:curso,curso',
            "version_id"=>"required|exists:version,id",
            "cohorte_id"=>"required|exists:cohorte,id"

        ]);
        if($validator->fails()){
            return response()->json([
                'res'=> false,
                'message' => $validator->errors()
            ], 400);
        }
       $cohorte = Cohorte::find($request->cohorte_id);

        if (!$cohorte) {
            return response()->json([
                'res' => false,
                'message' => 'La cohorte no existe'
            ], 400);
        }
        $curso = Curso::create([
            'curso'=>$request->curso,
            'version_id' => $request->version_id,
            'cohorte_id' => $request->cohorte_id
        ]);

        return response()->json([
            'res' => true,
            'message' => 'Curso creado correctamente',
            'data' => $curso
        ], 201);

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
    public function porAgno($id)
{
    $cursos = AgnoAcademico_Curso::with('curso')
        ->where('id_a_academico', $id)
        ->get();

    return response()->json(
        $cursos->map(function ($item) {
            return [
                'id' => $item->curso->id,
                'nombre' => $item->curso->curso
            ];
        })
    );
}

}
