<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPA;
use App\Models\PpaHistorial;
use App\Models\AgnoAcademico_Curso;
use App\Models\AñoAcademico;
use App\Models\Profesor;
use App\Models\Curso;
use App\Models\CatDocente;
use App\Models\CatCientifica;
use App\Models\ProgFormacion;
class PPAController extends Controller
{
    // 🟢 DESIGNAR
  public function designar(Request $request)
{
     $profesor = Profesor::find($request->id_profesor);
    // 🟡 VALIDAR PROFESOR
    if (!Profesor::where('id', $request->id_profesor)->exists()) {
        return response()->json([
            'error' => 'El profesor no existe'
        ], 400);
    }

    // 🔍 OBTENER CATEGORÍAS
$catDocente = CatDocente::find($profesor->idCatDocente);
$catCientifica = CatCientifica::find($profesor->idCatCientifica);

// 🔒 VALIDAR CATEGORÍA CIENTÍFICA
$validaCientifica = in_array($catCientifica->nombre, [
    'Investigador Auxiliar',
    'Investigador Titular'
]);

// 🔒 VALIDAR CATEGORÍA DOCENTE
$validaDocente = in_array($catDocente->nombre, [
    'Instructor',
    'Auxiliar',
    'Titular'
]);

if (!$validaCientifica || !$validaDocente) {
    return response()->json([
        'error' => 'El profesor no cumple con los requisitos para ser PPA'
    ], 400);
}
    $año = AñoAcademico::find($request->id_a_academico);

$existe = PPA::where('id_a_academico', $request->id_a_academico)
    ->whereHas('añoAcademico', function ($query) use ($año) {
        $query->where('id_programa_formacion', $año->id_programa_formacion);
    })
    ->exists();

if ($existe) {
    return response()->json([
        'error' => 'Ya existe un PPA asignado para esa carrera en ese año'
    ], 400);
}

if ($existe) {
    return response()->json([
        'error' => 'El profesor ya está designado como PPA en ese curso y año académico'
    ], 400);
}
    $existe = PPA::where('id_curso', $request->id_curso)
    ->where('id_a_academico', $request->id_a_academico)
    ->exists();

if ($existe) {
    return response()->json([
        'error' => 'Ya existe un PPA asignado para ese curso en ese año académico'
    ], 400);
}
    // 🟡 VALIDAR CURSO
    if (!Curso::where('id', $request->id_curso)->exists()) {
        return response()->json([
            'error' => 'El curso no existe'
        ], 400);
    }

    // 🟡 VALIDAR AÑO ACADÉMICO
    if (!AñoAcademico::where('id', $request->id_a_academico)->exists()) {
        return response()->json([
            'error' => 'El año académico no existe'
        ], 400);
    }

    // 🟢 VALIDACIÓN PRINCIPAL (curso pertenece al año)
    $valido = AgnoAcademico_Curso::where('id_curso', $request->id_curso)
        ->where('id_a_academico', $request->id_a_academico)
        ->exists();

    if (!$valido) {
        return response()->json([
            'error' => 'El curso no corresponde a ese año académico'
        ], 400);
    }

    // ✅ CREAR PPA
    $ppa = PPA::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso
    ]);

    // ✅ HISTORIAL
    PpaHistorial::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso,
        'accion' => 'designado',
        'fecha_accion' => now()
    ]);

   $año = AñoAcademico::find($request->id_a_academico);
$profesor = Profesor::find($request->id_profesor);
$carrera = ProgramaDeFormacion::find($año->id_programa_formacion);

$descripcion = "Se designó a {$profesor->nombre} {$profesor->apellidos} como PPA en la carrera {$carrera->nombre}, {$año->identificador}";

$usuario = $request->header('X-User') ?? 'desconocido';

LogController::registrar(
    $usuario,
    'designar_ppa',
    $descripcion
);
return response()->json($ppa);
}

    // 🔵 RATIFICAR
    public function ratificar(Request $request)
    {
        PpaHistorial::create([
            'id_profesor' => $request->id_profesor,
            'id_a_academico' => $request->id_a_academico,
            'id_curso' => $request->id_curso,
            'accion' => 'ratificado',
            'fecha_accion' => now()
        ]);
return response()->json(['message' => 'Ratificado']);
        $año = AñoAcademico::find($request->id_a_academico);
$profesor = Profesor::find($request->id_profesor);
$carrera = ProgramaDeFormacion::find($año->id_programa_formacion);

$descripcion = "Se ratificó a {$profesor->nombre} {$profesor->apellidos} como PPA en la carrera {$carrera->nombre}, {$año->identificador}";

$usuario = $request->header('X-User') ?? 'desconocido';

LogController::registrar(
    $usuario,
    'ratificar_ppa',
    $descripcion
);

    }

    // 🔴 DESNOMBRAR
    public function desnombrar(Request $request)
{
    $ppa = PPA::where('id_profesor', $request->id_profesor)
        ->where('id_curso', $request->id_curso)
        ->where('id_a_academico', $request->id_a_academico)
        ->first();

    if (!$ppa) {
        return response()->json([
            'error' => 'No existe PPA activo'
        ], 404);
    }

    // 🗑️ ELIMINAR de tabla actual
    $ppa->delete();

    // 📝 GUARDAR HISTORIAL
    PpaHistorial::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso,
        'accion' => 'desnombrado',
        'fecha_accion' => now()
    ]);

    return response()->json([
        'message' => 'PPA eliminado correctamente'
    ]);
   $año = AñoAcademico::find($request->id_a_academico);
$profesor = Profesor::find($request->id_profesor);
$carrera = ProgramaDeFormacion::find($año->id_programa_formacion);

$descripcion = "Se eliminó a {$profesor->nombre} {$profesor->apellidos} como PPA en la carrera {$carrera->nombre}, {$año->identificador}";

$usuario = $request->header('X-User') ?? 'desconocido';

LogController::registrar(
    $usuario,
    'desnombrar_ppa',
    $descripcion
);
}
public function index()
{
    $ppa = PPA::with([
        'profesor.catDocente',
        'profesor.catCientifica'
    ])->get();

    return response()->json(
        $ppa->map(function ($item) {
            return [
                'id' => $item->profesor->id,
                'nombre' => $item->profesor->nombre,
                'apellidos' => $item->profesor->apellidos,
                'catDocente' => $item->profesor->catDocente->nombre ?? 'No definida',
                'catCientifica' => $item->profesor->catCientifica->nombre ?? 'No definida'
            ];
        })
    );
}
}
