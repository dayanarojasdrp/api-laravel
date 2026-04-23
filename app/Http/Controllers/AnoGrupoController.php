<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnoGrupo;

class AnoGrupoController extends Controller
{
    public function index()
    {
        return AnoGrupo::with(['anoAcademico', 'grupo'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'ano_academico_id' => 'required|exists:a_academico,id',
            'grupo_id' => 'required|exists:grupos,id',
            'fecha' => 'nullable|date'
        ]);

        return AnoGrupo::create($request->all());
    }

    public function show($id)
    {
        return AnoGrupo::with(['anoAcademico', 'grupo'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = AnoGrupo::findOrFail($id);

        $request->validate([
            'ano_academico_id' => 'required|exists:a_academico,id',
            'grupo_id' => 'required|exists:grupos,id',
            'fecha' => 'nullable|date'
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        AnoGrupo::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
