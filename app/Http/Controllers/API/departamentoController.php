<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Departamento;
use App\Models\Facultad;
use App\Models\Curso;
use App\Models\Profesor;
use App\Models\HistorialFacultadDepartamento;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::all();
        return response()->json([
            'res' => true,
            'data' => $departamentos
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nombre" => "required",
            "abreviatura" => "required",
            "id_profesor" => "required|exists:profesor,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $departamento = Departamento::create([
            'nombre' => $request->nombre,
            'abreviatura' => $request->abreviatura,
            'id_profesor' => $request->id_profesor
        ]);

        if ($request->has('id_facultad') && $request->has('id_curso')) {
            $facultad = Facultad::find($request->id_facultad);
            $curso = Curso::find($request->id_curso);

            if (!$facultad || !$curso) {
                return response()->json([
                    'res' => false,
                    'message' => 'Facultad o curso no válidos, se creó el departamento pero no su historial'
                ], 400);
            }

            HistorialFacultadDepartamento::create([
                'id_departamento' => $departamento->id,
                'id_facultad' => $facultad->id,
                'id_curso' => $curso->id
            ]);
        }

        return response()->json([
            'res' => true,
            'message' => 'Departamento guardado correctamente'
        ], 200);
    }

    public function show(string $id)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el departamento'
            ], 400);
        }

        return response()->json([
            'res' => true,
            'data' => $departamento
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el departamento'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'unique:departamento,nombre,' . $id,
            'id_profesor' => 'nullable|exists:profesor,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if ($request->has('id_facultad') && $request->has('id_curso')) {
            $facultad = Facultad::find($request->id_facultad);
            $curso = Curso::find($request->id_curso);

            if ($facultad && $curso) {
                $historial = HistorialFacultadDepartamento::where('id_departamento', $departamento->id)
                    ->where('id_facultad', $facultad->id)
                    ->where('id_curso', $curso->id)
                    ->first();

                if (!$historial) {
                    HistorialFacultadDepartamento::create([
                        'id_departamento' => $departamento->id,
                        'id_facultad' => $facultad->id,
                        'id_curso' => $curso->id
                    ]);
                }
            }
        }

        $data = [];
        if ($request->has('nombre')) $data['nombre'] = $request->nombre;
        if ($request->has('abreviatura')) $data['abreviatura'] = $request->abreviatura;
        if ($request->has('id_profesor')) $data['id_profesor'] = $request->id_profesor;

        $departamento->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Departamento actualizado'
        ], 200);
    }

    public function destroy(string $id)
    {
        $departamento = Departamento::find($id);
        if (!$departamento) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el departamento a eliminar'
            ], 400);
        }

        $departamento->delete();

        return response()->json([
            'res' => true,
            'message' => 'Departamento eliminado satisfactoriamente'
        ], 200);
    }
}
