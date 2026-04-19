<?php

namespace App\Http\Controllers;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public static function registrar($usuario, $accion, $descripcion = null)
    {
        Log::create([
            'usuario' => $usuario,
            'accion' => $accion,
            'descripcion' => $descripcion
        ]);
    }
}
