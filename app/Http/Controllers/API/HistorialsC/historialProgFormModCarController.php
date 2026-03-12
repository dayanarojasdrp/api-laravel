<?php

namespace App\Http\Controllers\API\HistorialsC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgFormacion;
use App\Models\ModalidadCarrera;
use App\Models\ProgFormModalidadCarrera;
use Exception;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\IsTrue;

class historialProgFormModCarController extends Controller
{
    public function index()
    {
        $carr = ProgFormModalidadCarrera::all();
        return response()->json([
            'res'=> true,
            'data'=> $carr
        ], 200);
    }
    public function store(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id_modalidad'=> 'required',
            'id_prog_form'=> 'required'
        ]);
        if($val->fails()) {
            return response()->json([
                'res'=> false,
                'message'=>$val->errors(),
                'status'=> 400
            ], 400);
        }
        $mod = ModalidadCarrera::find($request->id_modalidad);
        $pF = ProgFormacion::find($request->id_prog_form);
        if(!$mod || !$pF) {
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontraron los recursos',
                'status'=> 400
            ], 400);
        }
        $check = ProgFormModalidadCarrera::where('id_modalidad', $request->id_modalidad)
            ->where('id_prog_form', $request->id_prog_form)->first();
        if($check){
            return response()->json([
                'res'=> false,
                'message'=> 'Ya existe un registro con esos datos',
                'status'=> 400
            ], 400);
        }
        $carr = ProgFormModalidadCarrera::create([
            'id_modalidad'=> $request->id_modalidad,
            'id_prog_form'=> $request->id_prog_form
        ]);
        if(!$carr) {
            return response()->json([
                'res'=> false,
                'message'=> 'No se pudo crear el registro',
                'status'=> 400
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Se creo correctamente el registro',
            'status'=> 200
        ], 200);
    }
    public function show(Request $request)
    {

    }
    public function update(Request $request, $idModalidad, $idProgForm)
    {
        $check = true;
        $data = [];
        if($request->has('id_modalidad')) {
            $mod = ModalidadCarrera::find($request->id_modalidad);
            if(!$mod) $check = false;
            $data['id_modalidad'] = $request->id_modalidad;
        }
        if($request->has('id_prog_form')) {
            $pF = ProgFormacion::find($request->id_prog_form);
            if(!$pF) $check = false;
            $data['id_prog_form'] = $request->id_prog_form;
        }
        if(!$check) {
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontraron los recursos',
                'status'=> 400
            ], 400);
        }
        if($request->has('id_modalidad') && $request->has('id_prog_form')){
            $check = ProgFormModalidadCarrera::where('id_modalidad', $request->id_modalidad)
                ->where('id_prog_form', $request->id_prog_form)->first();
            if($check){
                return response()->json([
                    'res'=> false,
                    'message'=> 'Ya existe otro registro con esos datos',
                    'status'=> 400
                ], 400);
            }
        }
        $carr = ProgFormModalidadCarrera::where('id_modalidad', $idModalidad)
            ->where('id_prog_form', $idProgForm)->first();
        $carr->update($data);
        if(!$carr) {
            return response()->json([
                'res'=> false,
                'message'=> 'No se pudo actualizar el registro',
                'status'=> 400
            ], 400);
        }
        return response()->json([
            'res'=> true,
            'message'=> 'Se actualizo correctamente el registro',
            'status'=> 200
        ], 200);
    }
    public function destroy($idModalidad, $idProgForm)
    {
        $carr = ProgFormModalidadCarrera::where('id_modalidad', $idModalidad)->where('id_prog_form', $idProgForm)->first();
        if(!$carr) {
            return response()->json([
                'res'=> false,
                'message'=> 'No se pudo encontrar el registro',
                'status'=> 400
            ], 400);
        }
        $register = $carr->delete();
        if(!$register) {
            return response()->json([
                'res'=>true,
                'message'=>'Fallo la eliminacion'
            ], 200);
        }
        return response()->json([
            'res'=>true,
            'message'=>'Registro eliminada satisfactoriamente'
        ], 200);
    }
}
