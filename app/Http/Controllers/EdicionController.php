<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Edicion;


class EdicionController extends Controller
{
    public function index()
    {
        return Edicion::with('tipo')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_id' => 'required|exists:tipos,id'
        ]);

        $edicion = Edicion::create($request->all());

        return response()->json($edicion, 201);
    }

    public function show($id)
    {
        return Edicion::with('tipo')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $edicion = Edicion::findOrFail($id);

        $request->validate([
            'tipo_id' => 'required|exists:tipos,id'
        ]);

        $edicion->update($request->all());

        return response()->json($edicion);
    }

    public function destroy($id)
    {
        Edicion::findOrFail($id)->delete();

        return response()->json(['message' => 'Eliminado']);
    }
}
