<?php

namespace App\Http\Controllers\API\HistorialsC;

use App\Http\Controllers\Controller;
use App\Models\Asignatura_Agno;
use Illuminate\Http\Request;

class asignaturaAgnoController extends Controller
{
    public function index() {
        return response()->json([
            'res' => true,
            'data' => Asignatura_Agno::all(),
            'status' => 200 
        ], 200);
    }
}
