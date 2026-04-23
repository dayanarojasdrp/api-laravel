<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Grupo;

class GrupoController extends Controller
{
    public function index()
    {
        return Grupo::all();
    }

    public function store(Request $request)
    {
        return Grupo::create();
    }

    public function show($id)
    {
        return Grupo::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $grupo = Grupo::findOrFail($id);

        // no hay nada que actualizar por ahora
        return $grupo;
    }

    public function destroy($id)
    {
        Grupo::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
