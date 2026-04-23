<?php

namespace App\Http\Controllers;


use App\Models\EdicionCurso;
use Illuminate\Http\Request;

class EdicionCursoController extends Controller
{
    public function index()
    {
        return EdicionCurso::with(['edicion', 'curso'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'edicion_id' => 'required|exists:ediciones,id',
            'curso_id' => 'required|exists:curso,id'
        ]);

        return EdicionCurso::create($request->all());
    }

    public function show($id)
    {
        return EdicionCurso::with(['edicion', 'curso'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = EdicionCurso::findOrFail($id);
        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        EdicionCurso::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
