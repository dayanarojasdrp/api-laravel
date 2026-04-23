<?php

namespace App\Http\Controllers;
use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Tipo::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $tipo = Tipo::create();

        return response()->json([
            'message' => 'Tipo creado correctamente',
            'data' => $tipo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
   public function show($id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json([
                'message' => 'Tipo no encontrado'
            ], 404);
        }

        return response()->json($tipo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json([
                'message' => 'Tipo no encontrado'
            ], 404);
        }

        $tipo->save();

        return response()->json([
            'message' => 'Tipo actualizado correctamente',
            'data' => $tipo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json([
                'message' => 'Tipo no encontrado'
            ], 404);
        }

        $tipo->delete();

        return response()->json([
            'message' => 'Tipo eliminado correctamente'
        ], 200);
    }
}
