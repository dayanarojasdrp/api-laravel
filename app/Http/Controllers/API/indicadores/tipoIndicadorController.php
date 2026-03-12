<?php

namespace App\Http\Controllers\API\indicadores;

use App\Http\Controllers\Controller;
use App\Models\TipoIndicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class tipoIndicadorController extends Controller
{
    public function index() {
        $tindicador = TipoIndicador::all();
        return response()->json([
            'res'=> true,
            'data'=> $tindicador,
            'status'=> 200
        ], 200);
    }

    public function store(Request $request) {
        $val = Validator::make($request->all(), [
            'nombre' => 'required'
        ]);
        if ($val->fails()) return response()->json(['res'=> false, 'message'=> $val->errors(), 'status'=> 400], 400);
        $tipo = TipoIndicador::create(['nombre'=> $request->nombre]);
        if (!$tipo) return response()->json(['res'=> false, 'message'=>'No se pudo crear el tipo de indicador', 'status'=> 400], 400);
        return response()->json(['res'=> true, 'message'=> 'Se creo correctamente el tipo de indicador', 'status'=> 200], 200);
    }

    public function show($id) {
        $tipo = TipoIndicador::find($id);
        if (!$tipo) return response()->json(['res'=> false, 'message'=>'No se encontro el tipo de indicador', 'status'=> 400], 400);
        return response()->json(['res'=> true, 'message'=> $tipo, 'status'=> 200], 200);
    }

    public function update(Request $request, $id) {
        $val = Validator::make($request->all(), [
            'nombre' => 'required'
        ]);
        if ($val->fails()) return response()->json(['res'=> false, 'message'=> $val->errors(), 'status'=> 400], 400);
        $tipo = TipoIndicador::find($id);
        if (!$tipo) return response()->json(['res'=> false, 'message'=>'No se encontro el tipo de indicador', 'status'=> 400], 400);
        $tipo = $tipo->update(['nombre'=> $request->nombre]);
        if (!$tipo) return response()->json(['res'=> false, 'message'=>'No se pudo actualizar el tipo de indicador', 'status'=> 400], 400);
        return response()->json(['res'=> true, 'message'=> 'Se actualizo correctamente el tipo de indicador', 'status'=> 200], 200);
    }
    
    public function destroy(Request $request, $id) {
        $tipo = TipoIndicador::find($id);
        if (!$tipo) return response()->json(['res'=> false, 'message'=>'No se encontro el tipo de indicador', 'status'=> 400], 400);
        $tipo->delete();
        return response()->json(['res'=> true, 'message'=>'Tipo de indicador eliminado satisfactoriamente', 'status'=> 200], 200);
    }
}
