<?php

namespace App\Http\Controllers;
use App\Models\Log;
use App\Services\ExternalUserService;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public static function registrar($usuario, $accion, $descripcion, $facultadId = null)
    {
        try {
            $usuario = app(ExternalUserService::class)->resolveLogUsername($usuario);
            $facultadId = $facultadId
                ?? request()->header('X-Facultad')
                ?? request('facultad_id')
                ?? request('id_facultad');

            Log::create([
                'usuario' => $usuario,
                'accion' => $accion,
                'descripcion' => $descripcion,
                'facultad_id' => is_numeric($facultadId) ? (int) $facultadId : null,
            ]);
        } catch (\Exception $e) {
            // 🔥 NO romper flujo principal
            \Log::error('Error guardando log: ' . $e->getMessage());
        }
    }
public function index(Request $request)
{
    $usuario = $request->query('usuario');

    $query = Log::query();

    // 🔥 SOLO filtra si te mandan usuario
    if ($usuario) {
        $query->where('usuario', $usuario);
    }

    $facultadId = $request->query('facultad_id')
        ?? $request->query('id_facultad')
        ?? $request->header('X-Facultad');

    if ($facultadId) {
        $query->where('facultad_id', $facultadId);
    }

    $logs = $query
        ->orderBy('created_at', 'desc')
        ->limit(10) // 🔥 siempre 10
        ->get(['usuario', 'accion', 'descripcion', 'facultad_id', 'created_at']);

    return response()->json($logs);
}
}
