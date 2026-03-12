<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistorialDepProgForm;
use App\Models\ProgFormacion;
use App\Models\Departamento;
use App\Models\Curso;
use Illuminate\Support\Facades\Validator;
class progFormController extends Controller
{
    public function index()
    {
        $pF = ProgFormacion::all();
        return response()->json([
            'res'=> true,
            'data'=> $pF
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre"=> "required",
            "abreviatura" => "required",
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        $pF = ProgFormacion::create ([
            'nombre'=> $request->nombre,
            'abreviatura' => $request->abreviatura
        ]);
        if ($request->has('id_departamento')) {
            $dep = Departamento::find($request->id_departamento);
            if(!$dep){
                return response()->json([
                    'res'=> false,
                    'message'=> 'El id de el departamento no existe, se creo el programa de formacion pero no un registro de donde pertenece'
                ], 400);
            }
            $cur = Curso::find($request->id_curso);
            if(!$cur){
                return response()->json([
                    'res'=> false,
                    "message" => 'No hay cursos anadinos, se creo el programa de formacion pero no un registro de donde pertenece'
                ], 400);
            }
            HistorialDepProgForm::create([
                'id_departamento'=> $dep->id,
                'id_prog_form'=> $pF->id,
                'id_curso' => $cur->id
            ]);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Programa de Formacion Guardado Correctamente ',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pF = ProgFormacion::find($id);
        if(!$pF){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro el programa de formacion'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'data'=> $pF
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pF = ProgFormacion::find($id);
        if(!$pF){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro el programa de formacion'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'unique:programa_de_formacion,nombre,'.$id,
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        if($request->has('id_departamento')){
            $dep = Departamento::find($request->id_departamento);
            if(!$dep){
                return response()->json([
                    'res'=>false,
                    'message'=> 'No se encontro el programa de formacion'
                ], 400);
            }
            $cur = Curso::find($request->id_curso);
            if(!$cur){
                return response()->json([
                    'res'=> false,
                    'message'=> 'No hay cursos anadidos'
                ]);
            }
            $hist = HistorialDepProgForm::where('id_departamento', $dep->id)
                ->where('id_prog_form', $pF->id)
                ->where('id_curso', $cur->id)->first();
            if(!$hist) {
                HistorialDepProgForm::create([
                    'id_departamento'=> $dep->id,
                    'id_prog_form'=> $pF->id,
                    'id_curso' => $cur->id
                ]);
            }
        }
        $data = [];
        if($request->has('nombre')) $data['nombre'] = $request->nombre;
        if($request->has('abreviatura')) $data['abreviatura'] = $request->abreviatura;
        $pF->update($data);
        return response()->json([
            'res' => true,
            'message' => 'programa de formacion actualizado'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pF = ProgFormacion::find($id);
        if(!$pF){
            return response()->json([
                'res'=>false,
                'message'=>'No se encontro el programa de formacion a eliminar'
            ], 400);
        }
        $pF->delete();
        return response()->json([
            'res'=>true,
            'message'=>'Programa de formacion eliminado satisfactoriamente'
        ], 200);
    }
}
