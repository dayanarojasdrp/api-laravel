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
}
