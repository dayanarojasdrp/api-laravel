<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use App\Models\Decano;

class DecanoController extends Controller
{
    // 🔹 LISTAR TODOS (con relaciones)
    public function index()
    {
        return Decano::with(['profesor', 'facultad'])->get();
    }

    // 🔹 CREAR NUEVO DECANO (CLAVE 🔥)
    public function store(Request $request)
{
    $request->validate([
        'id_facultad' => 'required|exists:facultad,id',
        'id_profesor' => 'required|exists:profesor,id',
        'fecha_inicio' => 'nullable|date'
    ]);

    // 🔥 VALIDACIÓN IMPORTANTE
    $pertenece = \App\Models\MiembroDepartamento::where('id_profesor', $request->id_profesor)
    ->where('habilitado', true)
    ->whereIn('id_departamento', function ($query) use ($request) {
        $query->select('id_departamento')
            ->from('facultad_departamento')
            ->where('id_facultad', $request->id_facultad);
    })
    ->exists();

    if (!$pertenece) {
        return response()->json([
            'error' => 'El profesor no pertenece a ningún departamento de esta facultad'
        ], 400);
    }

    // 🔥 DESACTIVAR DECANO ACTUAL
    Decano::where('id_facultad', $request->id_facultad)
        ->where('habilitado', true)
        ->update([
            'habilitado' => false,
            'fecha_fin' => now()
        ]);

    // 🔥 CREAR NUEVO DECANO
    return Decano::create([
        'id_facultad' => $request->id_facultad,
        'id_profesor' => $request->id_profesor,
        'fecha_inicio' => $request->fecha_inicio ?? now(),
        'fecha_fin' => null,
        'habilitado' => true
    ]);
}

    // 🔹 VER UNO
    public function show($uuid)
    {
        return Decano::with(['profesor', 'facultad'])
            ->findOrFail($uuid);
    }

    // 🔹 ACTUALIZAR (NO MUY USADO AQUÍ)
    public function update(Request $request, $uuid)
    {
        $decano = Decano::findOrFail($uuid);

        $request->validate([
            'id_facultad' => 'required|exists:facultad,id',
            'id_profesor' => 'required|exists:profesor,id'
        ]);

        $decano->update($request->all());

        return $decano;
    }

    // 🔹 ELIMINAR
    public function destroy($uuid)
    {
        Decano::findOrFail($uuid)->delete();

        return response()->json(['ok' => true]);
    }

    // ============================
    // 🔥 EXTRA PRO
    // ============================

    // 🔹 DECANO ACTUAL POR FACULTAD
    public function actual($facultadId)
    {
        return Decano::with('profesor')
            ->where('id_facultad', $facultadId)
            ->where('habilitado', true)
            ->first();
    }

    // 🔹 HISTORIAL DE FACULTAD
    public function historial($facultadId)
    {
        return Decano::with('profesor')
            ->where('id_facultad', $facultadId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }
}
