<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use Illuminate\Http\Request;

class asignaturaController extends Controller
{
    public function index() {
        return response()->json([
            'res' => true,
            'data' => Asignatura::all(),
            'status' => 200
        ], 200);
    }
}
