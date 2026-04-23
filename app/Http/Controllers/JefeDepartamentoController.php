<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MiembroDepartamento;
use App\Models\JefeDepartamento;

class JefeDepartamentoController extends Controller
{
    // 🔹 LISTAR TODOS
    public function index()
    {
        return JefeDepartamento::with(['profesor', 'departamento'])->get();
    }

    // 🔹 CREAR NUEVO JEFE (CLAVE 🔥)
  public function store(Request $request)
{
    $request->validate([
        'id_departamento' => 'required|exists:departamento,id',
        'id_profesor' => 'required|exists:profesor,id'
    ]);

    // 🔥 VALIDACIÓN DE NEGOCIO
    $esMiembro = MiembroDepartamento::where('id_profesor', $request->id_profesor)
        ->where('id_departamento', $request->id_departamento)
        ->where('habilitado', true)
        ->exists();

    if (!$esMiembro) {
        return response()->json([
            'error' => 'El profesor no es miembro activo de este departamento'
        ], 422);
    }

    // 🔥 desactivar actual
    JefeDepartamento::where('id_departamento', $request->id_departamento)
        ->where('habilitado', true)
        ->update([
            'habilitado' => false,
            'fecha_fin' => now()
        ]);

    // 🔥 crear nuevo
    return JefeDepartamento::create([
        'id_departamento' => $request->id_departamento,
        'id_profesor' => $request->id_profesor,
        'fecha_inicio' => now(),
        'habilitado' => true
    ]);
}

    // 🔹 VER UNO
    public function show($uuid)
    {
        return JefeDepartamento::with(['profesor', 'departamento'])
            ->findOrFail($uuid);
    }

    // 🔹 ACTUALIZAR
    public function update(Request $request, $uuid)
    {
        $item = JefeDepartamento::findOrFail($uuid);

        $request->validate([
            'id_departamento' => 'required|exists:departamento,id',
            'id_profesor' => 'required|exists:profesor,id'
        ]);

        $item->update($request->all());

        return $item;
    }

    // 🔹 ELIMINAR
    public function destroy($uuid)
    {
        JefeDepartamento::findOrFail($uuid)->delete();

        return response()->json(['ok' => true]);
    }

    // ============================
    // 🔥 EXTRA PRO
    // ============================

    // 🔹 JEFE ACTUAL POR DEPARTAMENTO
    public function actual($departamentoId)
    {
        return JefeDepartamento::with('profesor')
            ->where('id_departamento', $departamentoId)
            ->where('habilitado', true)
            ->first();
    }

    // 🔹 HISTORIAL
    public function historial($departamentoId)
    {
        return JefeDepartamento::with('profesor')
            ->where('id_departamento', $departamentoId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }
}
