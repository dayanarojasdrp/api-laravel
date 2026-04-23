<?php

namespace App\Http\Controllers;





use Illuminate\Http\Request;
use App\Models\ProfesorGuia;

class ProfesorGuiaController extends Controller
{
    // 🔹 listar
    public function index()
    {
        return ProfesorGuia::with(['profesor', 'grupo'])->get();
    }

    // 🔹 crear (CLAVE 🔥)
    public function store(Request $request)
    {
        $request->validate([
            'id_profesor' => 'required|exists:profesor,id',
            'id_grupo' => 'required|exists:grupos,id',
            'fecha_inicio' => 'nullable|date'
        ]);

        // 🔥 cerrar el actual del grupo
        ProfesorGuia::where('id_grupo', $request->id_grupo)
            ->where('habilitado', true)
            ->update([
                'habilitado' => false,
                'fecha_fin' => now()
            ]);

        // 🔥 crear nuevo
        return ProfesorGuia::create([
            'id_profesor' => $request->id_profesor,
            'id_grupo' => $request->id_grupo,
            'fecha_inicio' => $request->fecha_inicio ?? now(),
            'fecha_fin' => null,
            'habilitado' => true
        ]);
    }

    // 🔹 ver uno
    public function show($id)
    {
        return ProfesorGuia::with(['profesor', 'grupo'])->findOrFail($id);
    }

    // 🔹 actualizar
    public function update(Request $request, $id)
    {
        $item = ProfesorGuia::findOrFail($id);
        $item->update($request->all());

        return $item;
    }

    // 🔹 eliminar
    public function destroy($id)
    {
        ProfesorGuia::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    // ============================
    // 🔥 EXTRA
    // ============================

    // 🔹 actual por grupo
    public function actual($grupoId)
    {
        return ProfesorGuia::with('profesor')
            ->where('id_grupo', $grupoId)
            ->where('habilitado', true)
            ->first();
    }

    // 🔹 historial
    public function historial($grupoId)
    {
        return ProfesorGuia::with('profesor')
            ->where('id_grupo', $grupoId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }
}
