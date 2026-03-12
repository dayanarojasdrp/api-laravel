<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Facultad;
use App\Models\HistorialUniversidadFacultad;
use App\Models\Municipio;
use App\Models\Universidad;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class universidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'res'=> true,
            'data'=> Universidad::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'=> 'required| max: 50| min: 10|unique:Universidad',
            'abreviatura' => 'required| max: 10| min: 1',
            'nivelDeAcreditacion' => 'required|in:Avalada,Certificada,Excelencia',
            'direccion'=>'required',
            'id_municipio' => 'required',
            'id_profesor' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=> false,
                'message' => $validator->errors()
            ], 400);
        }
        $idM = Municipio::find($request->id_municipio);
        if(!$idM){
            return response()->json([
                'res'=>false,
                'message'=> 'Error el id del municipio no existe'
            ], 400);
        }
        $idP = Profesor::find($request->id_profesor);
        if(!$idP){
            return response()->json([
                'res'=>false,
                'message'=> 'Error el id del profesor no existe'
            ], 400);
        }
        $uni = Universidad::create([
            'nombre' => $request->nombre,
            'abreviatura'=>$request->abreviatura,
            'nivelDeAcreditacion'=>$request->nivelDeAcreditacion,
            'direccion'=>$request->direccion,
            'id_municipio'=>$request->id_municipio,
            'id_provincia'=> $idM->id_provincia,
            'id_profesor'=> $request->id_profesor
        ]);
        return response()->json([
            'res'=> true,
            'message'=> 'Se anadio la universidad correctamente'
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $uni = Universidad::find($id);
        if(!$uni){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro la universidad'
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'data'=> $uni
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $uni = Universidad::find($id);
        if(!$uni){
            return response()->json([
                'res'=>false,
                'message'=> 'No se encontro la universidad'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'unique:universidad,nombre,'.$id,
            'nivelDeAcreditacion' => 'in:Avalada,Certificada,Excelencia',
        ]);
        if($validator->fails()){
            return response()->json([
                'res'=>false,
                'message'=> $validator->errors()
            ], 400);
        }
        if($request->has('id_municipio')){
            $idM = Municipio::find($request->id_municipio);
            if(!$idM){
                return response()->json([
                    'res'=>false,
                    'message'=> 'Error no se encontro el municipio'
                ], 400);
            }
            $idP = Profesor::find($request->id_profesor);
            if(!$idP){
                return response()->json([
                    'res'=>false,
                    'message'=> 'Error el id del profesor no existe'
                ], 400);
            }
            $uni->id_provincia = $idM->id_provincia;
            $uni->id_profesor = $idP->id;

        }
        $uni->update($request->all());
        return response()->json([
            'res' => true,
            'message' => 'Universidad actualizada'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $uni = Universidad::find($id);
        if(!$uni){
            return response()->json([
                'res'=>false,
                'message'=>'No se encontro la universidad a eliminar'
            ], 400);
        }
        //Esta linea es para eliminar los registros de una universidad cuando esta se elimina
        HistorialUniversidadFacultad::where('id_universidad', $uni->id)->delete();
        //Esta para cambiar el id de universidad de las facultades que esten dentro de esta
        $uni->delete();
        return response()->json([
            'res'=>true,
            'message'=>'Universidad eliminada satisfactoriamente'
        ], 200);
    }
}
