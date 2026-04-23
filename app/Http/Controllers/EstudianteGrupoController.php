<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EstudianteGrupo;

class EstudianteGrupoController extends Controller
{
    public function index()
    {
        return EstudianteGrupo::with(['estudiante', 'grupo'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'grupo_id' => 'required|exists:grupos,id',
            'fecha' => 'nullable|date'
        ]);

        return EstudianteGrupo::create($request->all());
    }

    public function show($id)
    {
        return EstudianteGrupo::with(['estudiante', 'grupo'])
            ->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = EstudianteGrupo::findOrFail($id);

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'grupo_id' => 'required|exists:grupos,id',
            'fecha' => 'nullable|date'
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        EstudianteGrupo::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
