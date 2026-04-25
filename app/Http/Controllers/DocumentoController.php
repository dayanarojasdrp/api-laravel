<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{


private function guardarDocumento($nombre, $tipo, $tipoDoc, $contenido, $extension)
{
    $fecha = now()->format('Y-m-d_H-i-s');

    $nombreArchivo = "{$fecha}_{$tipoDoc}_{$tipo}.{$extension}";
    $ruta = "documentos/{$nombreArchivo}";

    // 🔥 guardar archivo
    Storage::put($ruta, $contenido);

    // 🔥 guardar en BD
    Documento::create([
        'nombre' => "{$tipoDoc} {$tipo} ({$fecha})",
        'tipo' => $tipo,
        'tipo_documento' => $tipoDoc,
        'periodo' => now()->year,
        'ruta' => $ruta
    ]);

    return $ruta;
}
public function index(Request $request)
{
    $query = \App\Models\Documento::query();

    // 🔹 filtro por tipo (ppa / aa)
    if ($request->tipo && $request->tipo !== 'todos') {
        $query->where('tipo', $request->tipo);
    }

    // 🔹 filtro por periodo (año)
    if ($request->periodo && $request->periodo !== 'todos') {
        $query->where('periodo', $request->periodo);
    }

    $docs = $query->orderBy('created_at', 'desc')->get();

    return response()->json($docs);
}
}
