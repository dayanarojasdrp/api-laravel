<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cohorte;
use App\Models\Curso;
use App\Models\Version;
use Illuminate\Support\Facades\Validator;

class CohorteController extends Controller
{
    public function index()
    {
        $cohortes = Cohorte::all();

        return response()->json([
            'res' => true,
            'data' => $cohortes
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "curso_inicio" => "required|exists:curso,id",
            "curso_fin" => "required|exists:curso,id",
            "version_id" => "required|exists:version,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

       

        $cohorte = Cohorte::create([
            'curso_inicio' => $request->curso_inicio,
            'curso_fin' => $request->curso_fin,
            'version_id' => $request->version_id
        ]);

        return response()->json([
            'res' => true,
            'message' => 'Cohorte creada correctamente',
            'data' => $cohorte
        ], 201);
    }

    public function destroy($id)
    {
        $cohorte = Cohorte::find($id);

        if (!$cohorte) {
            return response()->json([
                'res' => false,
                'message' => 'Cohorte no encontrada'
            ], 404);
        }

        $cohorte->delete();

        return response()->json([
            'res' => true,
            'message' => 'Cohorte eliminada correctamente'
        ], 200);
    }
}
