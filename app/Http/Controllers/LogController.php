<?php

namespace App\Http\Controllers;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public static function registrar($usuario, $accion, $descripcion)
    {
        try {
            Log::create([
                'usuario' => $usuario,
                'accion' => $accion,
                'descripcion' => $descripcion
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

    $logs = $query
        ->orderBy('created_at', 'desc')
        ->limit(10) // 🔥 siempre 10
        ->get(['accion', 'descripcion', 'created_at']);

    return response()->json($logs);
}
}
