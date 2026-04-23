<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\GradoTitulo;

class GradoTituloController extends Controller
{
    public function index()
    {
        return GradoTitulo::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        return GradoTitulo::create($request->all());
    }

    public function show($id)
    {
        return GradoTitulo::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = GradoTitulo::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        GradoTitulo::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
