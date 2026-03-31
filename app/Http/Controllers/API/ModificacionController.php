<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modificacion;
use App\Models\Version;
use Illuminate\Support\Facades\Validator;

class ModificacionController extends Controller
{
     public function index()
    {
        $modificacion = Modificacion::all();

        return response()->json([
            'res' => true,
            'data' => $modificacion
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre" => "required|string",
            "version_id" => "required|exists:version,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $modificacion = Modificacion::create([
            'nombre' => $request->nombre,
            
            'version_id' => $request->version_id
        ]);

        return response()->json([
            'res' => true,
            'message' => 'Modificacion creada correctamente',
            'data' => $modificacion
        ], 201);
    }

     public function show($id)
    {
        $modificacion = Modificacion::with('version')->find($id);

        if (!$modificacion) {
            return response()->json([
                'res' => false,
                'message' => 'Modificacion  no encontrada'
            ], 404);
        }

        return response()->json([
            'res' => true,
            'data' => [
                'id' => $modificacion->id,
                'nombre' => $modificacion->nombre,
                'version_id' => $modificacion->version_id
            ]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $modificacion = Modificacion::find($id);

        if (!$modificacion) {
            return response()->json([
                'res' => false,
                'message' => 'Modificacion no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            "nombre" => "sometimes|string",
            "version_id" => "sometimes|exists:version,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $data = [];

        if ($request->has('nombre')) $data['nombre'] = $request->nombre;
        
        if ($request->has('version_id')) $data['version_id'] = $request->version_id;

        $modificacion->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Modificacion actualizada correctamente'
        ], 200);
    }

    public function destroy($id)
    {
        $modificacion = Modificacion::find($id);

        if (!$modificacion) {
            return response()->json([
                'res' => false,
                'message' => 'Modificacion no encontrada'
            ], 404);
        }

        $modificacion->delete();

        return response()->json([
            'res' => true,
            'message' => 'Modificacion eliminada correctamente'
        ], 200);
    }
}
