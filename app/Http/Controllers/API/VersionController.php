<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Version;
use App\Models\PlanEstudio;
use Illuminate\Support\Facades\Validator;

class VersionController extends Controller
{
    public function index()
    {
        $versiones = Version::all();

        return response()->json([
            'res' => true,
            'data' => $versiones
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre" => "required|string",
            "plan_estudio_id" => "required|exists:plan-estudio,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $version = Version::create([
            'nombre' => $request->nombre,
            
            'plan_estudio_id' => $request->plan_estudio_id
        ]);

        return response()->json([
            'res' => true,
            'message' => 'Versión creada correctamente',
            'data' => $version
        ], 201);
    }

     public function show($id)
    {
        $version = Version::with('planEstudio')->find($id);

        if (!$version) {
            return response()->json([
                'res' => false,
                'message' => 'Versión no encontrada'
            ], 404);
        }

        return response()->json([
            'res' => true,
            'data' => [
                'id' => $version->id,
                'nombre' => $version->nombre,
                'plan_estudio_id' => $version->plan_estudio_id
            ]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $version = Version::find($id);

        if (!$version) {
            return response()->json([
                'res' => false,
                'message' => 'Versión no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            "nombre" => "sometimes|string",
            "plan_estudio_id" => "sometimes|exists:plan-estudio,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $data = [];

        if ($request->has('nombre')) $data['nombre'] = $request->nombre;
        
        if ($request->has('plan_estudio_id')) $data['plan_estudio_id'] = $request->plan_estudio_id;

        $version->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Versión actualizada correctamente'
        ], 200);
    }

    public function destroy($id)
    {
        $version = Version::find($id);

        if (!$version) {
            return response()->json([
                'res' => false,
                'message' => 'Versión no encontrada'
            ], 404);
        }

        $version->delete();

        return response()->json([
            'res' => true,
            'message' => 'Versión eliminada correctamente'
        ], 200);
    }
}
