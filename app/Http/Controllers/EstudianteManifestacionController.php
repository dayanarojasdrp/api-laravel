<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EstudianteManifestacion;

class EstudianteManifestacionController extends Controller
{
    public function index()
    {
        return EstudianteManifestacion::with(['estudiante', 'manifestacion'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'manifestacion_id' => 'required|exists:manifestaciones,id',
            'fecha' => 'nullable|date'
        ]);

        return EstudianteManifestacion::create($request->all());
    }

    public function show($id)
    {
        return EstudianteManifestacion::with(['estudiante', 'manifestacion'])
            ->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = EstudianteManifestacion::findOrFail($id);

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'manifestacion_id' => 'required|exists:manifestaciones,id',
            'fecha' => 'nullable|date'
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        EstudianteManifestacion::findOrFail($id)->delete();

        return response()->json([
            'ok' => true
        ]);
    }
}
