<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\SectorEstrategico;

class SectorEstrategicoController extends Controller
{
    public function index()
    {
        return SectorEstrategico::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        return SectorEstrategico::create($request->all());
    }

    public function show($id)
    {
        return SectorEstrategico::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = SectorEstrategico::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        SectorEstrategico::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
