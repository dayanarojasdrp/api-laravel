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

    $logs = Log::where('usuario', $usuario)
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get(['accion', 'descripcion', 'created_at']);

    return response()->json($logs);
}
}
