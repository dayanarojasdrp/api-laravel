<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\TD_PP;

class TDPPController extends Controller
{
    public function index()
    {
        return TD_PP::with('sectorEstrategico')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'desarrollo_local' => 'required|string|max:255',
            'sector_estrategico_id' => 'required|exists:sector_estrategicos,id'
        ]);

        return TD_PP::create($request->all());
    }

    public function show($id)
    {
        return TD_PP::with('sectorEstrategico')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = TD_PP::findOrFail($id);

        $request->validate([
            'desarrollo_local' => 'required|string|max:255',
            'sector_estrategico_id' => 'required|exists:sector_estrategicos,id'
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        TD_PP::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
