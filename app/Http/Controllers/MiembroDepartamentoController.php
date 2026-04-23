<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MiembroDepartamento;

class MiembroDepartamentoController extends Controller
{
    // 🔹 listar
    public function index()
    {
        return MiembroDepartamento::with(['profesor', 'departamento'])->get();
    }

    // 🔹 agregar miembro
    public function store(Request $request)
    {
        $request->validate([
            'id_profesor' => 'required|exists:profesor,id',
            'id_departamento' => 'required|exists:departamento,id',
            'fecha_inicio' => 'nullable|date'
        ]);

        return MiembroDepartamento::create([
            'id_profesor' => $request->id_profesor,
            'id_departamento' => $request->id_departamento,
            'fecha_inicio' => $request->fecha_inicio ?? now(),
            'fecha_fin' => null,
            'habilitado' => true
        ]);
    }

    // 🔹 ver uno
    public function show($id)
    {
        return MiembroDepartamento::with(['profesor', 'departamento'])
            ->findOrFail($id);
    }

    // 🔹 actualizar (poco usado)
    public function update(Request $request, $id)
    {
        $item = MiembroDepartamento::findOrFail($id);

        $item->update($request->all());

        return $item;
    }

    // 🔹 eliminar
    public function destroy($id)
    {
        MiembroDepartamento::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }

    // ============================
    // 🔥 EXTRA (MUY ÚTIL)
    // ============================

    // 🔹 miembros activos de un departamento
    public function activos($departamentoId)
    {
        return MiembroDepartamento::with('profesor')
            ->where('id_departamento', $departamentoId)
            ->where('habilitado', true)
            ->get();
    }

    // 🔹 historial de un profesor
    public function historialProfesor($profesorId)
    {
        return MiembroDepartamento::with('departamento')
            ->where('id_profesor', $profesorId)
            ->get();
    }
}
