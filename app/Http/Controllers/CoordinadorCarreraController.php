<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\CoordinadorCarrera;

class CoordinadorCarreraController extends Controller
{
    // 🔹 LISTAR TODOS
    public function index()
    {
        return CoordinadorCarrera::with(['profesor', 'programa'])->get();
    }

    // 🔹 CREAR (CLAVE 🔥)
    public function store(Request $request)
    {
        $request->validate([
            'id_prog_form' => 'required|exists:programa_de_formacion,id',
            'id_profesor' => 'required|exists:profesor,id',
            'fecha_inicio' => 'nullable|date'
        ]);

        // 🔥 DESACTIVAR ACTUAL
        CoordinadorCarrera::where('id_prog_form', $request->id_prog_form)
            ->where('habilitado', true)
            ->update([
                'habilitado' => false,
                'fecha_fin' => now()
            ]);

        // 🔥 CREAR NUEVO
        return CoordinadorCarrera::create([
            'id_prog_form' => $request->id_prog_form,
            'id_profesor' => $request->id_profesor,
            'fecha_inicio' => $request->fecha_inicio ?? now(),
            'fecha_fin' => null,
            'habilitado' => true
        ]);
    }

    // 🔹 VER UNO
    public function show($uuid)
    {
        return CoordinadorCarrera::with(['profesor', 'programa'])
            ->findOrFail($uuid);
    }

    // 🔹 ACTUALIZAR (NO RECOMENDADO PARA CAMBIAR COORDINADOR)
    public function update(Request $request, $uuid)
    {
        $item = CoordinadorCarrera::findOrFail($uuid);

        $request->validate([
            'id_prog_form' => 'required|exists:programa_de_formacion,id',
            'id_profesor' => 'required|exists:profesor,id'
        ]);

        $item->update($request->all());

        return $item;
    }

    // 🔹 ELIMINAR
    public function destroy($uuid)
    {
        CoordinadorCarrera::findOrFail($uuid)->delete();

        return response()->json(['ok' => true]);
    }

    // ============================
    // 🔥 EXTRA (MUY ÚTIL)
    // ============================

    // 🔹 COORDINADOR ACTUAL POR PROGRAMA
    public function actual($programaId)
    {
        return CoordinadorCarrera::with('profesor')
            ->where('id_prog_form', $programaId)
            ->where('habilitado', true)
            ->first();
    }

    // 🔹 HISTORIAL
    public function historial($programaId)
    {
        return CoordinadorCarrera::with('profesor')
            ->where('id_prog_form', $programaId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }
}
