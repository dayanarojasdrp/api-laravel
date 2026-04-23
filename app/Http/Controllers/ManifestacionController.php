<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Manifestacion;


class ManifestacionController extends Controller
{
    /**
     * Listar todas
     */
    public function index()
    {
        return Manifestacion::with('edicion')->get();
    }

    /**
     * Crear
     */
    public function store(Request $request)
    {
        $request->validate([
            'edicion_id' => 'required|exists:ediciones,id'
        ]);

        $manifestacion = Manifestacion::create([
            'edicion_id' => $request->edicion_id
        ]);

        return response()->json($manifestacion, 201);
    }

    /**
     * Mostrar una
     */
    public function show(string $id)
    {
        return Manifestacion::with('edicion')->findOrFail($id);
    }

    /**
     * Actualizar
     */
    public function update(Request $request, string $id)
    {
        $manifestacion = Manifestacion::findOrFail($id);

        $request->validate([
            'edicion_id' => 'required|exists:ediciones,id'
        ]);

        $manifestacion->update([
            'edicion_id' => $request->edicion_id
        ]);

        return response()->json($manifestacion);
    }

    /**
     * Eliminar
     */
    public function destroy(string $id)
    {
        $manifestacion = Manifestacion::findOrFail($id);
        $manifestacion->delete();

        return response()->json([
            'message' => 'Eliminado correctamente'
        ]);
    }
}
