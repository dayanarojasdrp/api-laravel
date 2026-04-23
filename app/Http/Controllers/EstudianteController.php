<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\Estudiante;


class EstudianteController extends Controller
{



    public function index()
    {
        return response()->json([
            'data' => Estudiante::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $estudiante = Estudiante::create([
            'nombre' => $request->nombre
        ]);

        return response()->json($estudiante);
    }
}

