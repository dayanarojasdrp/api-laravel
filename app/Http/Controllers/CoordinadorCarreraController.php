<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\CoordinadorCarrera;

class CoordinadorCarreraController extends Controller
{
    // 🔹 LISTAR TODOS
    public function index()
    {
        return CoordinadorCarrera::with(['profesor', 'programa'])->get();
    }

    // 🔹 CREAR (CLAVE 🔥)
    use Illuminate\Support\Facades\DB;

public function store(Request $request)
{
    $request->validate([
        'id_prog_form' => 'required|exists:programa_de_formacion,id',
        'id_profesor' => 'required|exists:profesor,id',
        'fecha_inicio' => 'nullable|date'
    ]);

    // 🔥 VALIDAR RELACIÓN PROFESOR → DEPARTAMENTO → PROGRAMA
    $valido = DB::table('miembro_departamento as md')
        ->join('departamento_prog_d_form as dp', 'md.id_departamento', '=', 'dp.id_departamento')
        ->where('md.id_profesor', $request->id_profesor)
        ->where('md.habilitado', true)
        ->where('dp.id_prog_form', $request->id_prog_form)
        ->exists();

    if (!$valido) {
        return response()->json([
            'error' => 'El profesor no pertenece al departamento de esta carrera'
        ], 400);
    }

    // 🔥 DESACTIVAR COORDINADOR ACTUAL
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
