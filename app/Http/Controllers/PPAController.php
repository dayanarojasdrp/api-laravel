<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PPA;
use App\Models\PpaHistorial;
use App\Models\AgnoAcademico_Curso;
use App\Models\AnoAcademico;
use App\Models\Profesor;
use App\Models\Curso;
use App\Models\CatDocente;
use App\Models\CatCientifica;
use App\Models\ProgFormacion;
use App\Http\Controllers\LogController;
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
if (!$catDocente || !$catCientifica) {
    return response()->json([
        'error' => 'El profesor no tiene categorías definidas'
    ], 400);
}

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
   $ano = AnoAcademico::find($request->id_a_academico);

if (!$ano) {
    return response()->json([
        'error' => 'Ano académico inválido'
    ], 400);
}
$carrera = ProgFormacion::find($ano->id_prog_form);

if (!$carrera) {
    return response()->json([
        'error' => 'Programa de formación no válido'
    ], 400);
}


// 🔹 validar por carrera
$existeCarrera = PPA::where('id_a_academico', $request->id_a_academico)
    ->exists();

if ($existeCarrera) {
    return response()->json([
        'error' => 'Ya existe un PPA para ese ano académico'
    ], 400);
}

// 🔹 validar profesor duplicado
$existeProfesor = PPA::where('id_profesor', $request->id_profesor)
    ->where('id_a_academico', $request->id_a_academico)
    ->exists();

if ($existeProfesor) {
    return response()->json([
        'error' => 'El profesor ya está designado'
    ], 400);
}
    $existe = PPA::where('id_curso', $request->id_curso)
    ->where('id_a_academico', $request->id_a_academico)
    ->exists();

if ($existe) {
    return response()->json([
        'error' => 'Ya existe un PPA asignado para ese curso en ese ano académico'
    ], 400);
}
    // 🟡 VALIDAR CURSO
    if (!Curso::where('id', $request->id_curso)->exists()) {
        return response()->json([
            'error' => 'El curso no existe'
        ], 400);
    }

    // 🟡 VALIDAR ANO ACADÉMICO
    if (!AnoAcademico::where('id', $request->id_a_academico)->exists()) {
        return response()->json([
            'error' => 'El ano académico no existe'
        ], 400);
    }

    // 🟢 VALIDACIÓN PRINCIPAL (curso pertenece al ano)
    $valido = AgnoAcademico_Curso::where('id_curso', $request->id_curso)
        ->where('id_a_academico', $request->id_a_academico)
        ->exists();

    if (!$valido) {
        return response()->json([
            'error' => 'El curso no corresponde a ese ano académico'
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


$profesor = Profesor::find($request->id_profesor);
$carrera = ProgFormacion::find($ano->id_prog_form);
if (!$carrera) {
    return response()->json(['error' => 'Carrera null'], 500);
}

if (!$profesor) {
    return response()->json(['error' => 'Profesor null'], 500);
}
$profesor = Profesor::find($request->id_profesor);
$carrera = ProgFormacion::find($ano->id_prog_form);

if (!$profesor || !$carrera) {
    return response()->json([
        'error' => 'Datos insuficientes para registrar log'
    ], 400);
}
$descripcion = "Se designó a {$profesor->nombre} {$profesor->apellidos} como PPA en la carrera {$carrera->nombre}, {$ano->identificador}";

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

    // 🔥 MOVER TODO ESTO ARRIBA
    $ano = AnoAcademico::find($request->id_a_academico);
    $profesor = Profesor::find($request->id_profesor);
    $carrera = ProgFormacion::find($ano->id_prog_form);

    $descripcion = "Se ratificó a {$profesor->nombre} {$profesor->apellidos} como PPA en la carrera {$carrera->nombre}, {$ano->identificador}";

    $usuario = $request->header('X-User') ?? 'desconocido';

    LogController::registrar(
        $usuario,
        'ratificar_ppa',
        $descripcion
    );

    // ✅ AHORA SÍ RETURN AL FINAL
    return response()->json(['message' => 'Ratificado']);
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

    $ppa->delete();

    PpaHistorial::create([
        'id_profesor' => $request->id_profesor,
        'id_a_academico' => $request->id_a_academico,
        'id_curso' => $request->id_curso,
        'accion' => 'desnombrado',
        'fecha_accion' => now()
    ]);

    // 🔥 LOG ANTES DEL RETURN
    $ano = AnoAcademico::find($request->id_a_academico);
    $profesor = Profesor::find($request->id_profesor);
    $carrera = ProgFormacion::find($ano->id_prog_form);

    $descripcion = "Se eliminó a {$profesor->nombre} {$profesor->apellidos} como PPA en la carrera {$carrera->nombre}, {$ano->identificador}";

    $usuario = $request->header('X-User') ?? 'desconocido';

    LogController::registrar(
        $usuario,
        'desnombrar_ppa',
        $descripcion
    );

    return response()->json([
        'message' => 'PPA eliminado correctamente'
    ]);
}
public function index()
{
    $ppa = PPA::with([
        'profesor.catDocente',
        'profesor.catCientifica'
    ])->get();

    return response()->json(
        $ppa->map(function ($item) {

            // 🔥 OBTENER ANO
            $anio = \App\Models\AnoAcademico::find($item->id_a_academico);

            // 🔥 OBTENER CARRERA
            $carrera = $anio
                ? \App\Models\ProgFormacion::find($anio->id_prog_form)
                : null;

            // 🔥 OBTENER DEPARTAMENTO

$departamento = DB::table('departamento_prog_d_form')
    ->join('departamento', 'departamento_prog_d_form.id_departamento', '=', 'departamento.id')
    ->where('departamento_prog_d_form.id_prog_form', $carrera->id)
    ->select('departamento.nombre')
    ->first();

            return [
                'id' => $item->profesor->id,
                'nombre' => $item->profesor->nombre,
                'apellidos' => $item->profesor->apellidos,
                'catDocente' => $item->profesor->catDocente->nombre ?? 'No definida',
                'catCientifica' => $item->profesor->catCientifica->nombre ?? 'No definida',

                // 🔥 ESTO YA LO TENÍAS (NO SE TOCA)
                'id_curso' => $item->id_curso,
                'id_a_academico' => $item->id_a_academico,

                // ✅ NUEVO (LO QUE QUIERES MOSTRAR)
                'departamento' => $departamento->nombre ?? null,
                'carrera' => $carrera->nombre ?? '',
                'anio' => $anio->identificador ?? ''
            ];
        })
    );
}
}
