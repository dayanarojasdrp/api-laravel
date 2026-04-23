<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EstudianteTDPP;

class EstudianteTDPPController extends Controller
{
    public function index()
    {
        return EstudianteTDPP::with(['estudiante', 'tdpp'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'td_pp_id' => 'required|exists:td_pp,id',
            'fecha' => 'nullable|date'
        ]);

        return EstudianteTDPP::create($request->all());
    }

    public function show($id)
    {
        return EstudianteTDPP::with(['estudiante', 'tdpp'])
            ->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $item = EstudianteTDPP::findOrFail($id);

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'td_pp_id' => 'required|exists:td_pp,id',
            'fecha' => 'nullable|date'
        ]);

        $item->update($request->all());

        return $item;
    }

    public function destroy($id)
    {
        EstudianteTDPP::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
