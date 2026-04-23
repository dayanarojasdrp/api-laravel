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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\Shared\Html;
use Carbon\Carbon;
use App\Models\Decano;





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
public function exportPDF()
{
    $data = $this->getPPAData();

    $pdf = Pdf::loadView('exports.ppa', ['data' => $data]);

    return $pdf->download('ppa.pdf');
}

public function exportWord()
{
    $data = $this->getPPAData();

    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    $section->addText("Listado de PPA", ['bold' => true]);

    foreach ($data as $item) {
        $section->addText(
            "{$item['nombre']} {$item['apellidos']} | {$item['carrera']} | {$item['anio']}"
        );
    }

    $file = storage_path('app/ppa.docx');

    IOFactory::createWriter($phpWord, 'Word2007')->save($file);

    return response()->download($file)->deleteFileAfterSend(true);
}
private function getPPAData()
{
    $ppa = PPA::with([
        'profesor.catDocente',
        'profesor.catCientifica'
    ])->get();

    return $ppa->map(function ($item) {

        $anio = \App\Models\AnoAcademico::find($item->id_a_academico);

        $carrera = $anio
            ? \App\Models\ProgFormacion::find($anio->id_prog_form)
            : null;

        $departamento = DB::table('departamento_prog_d_form')
            ->join('departamento', 'departamento_prog_d_form.id_departamento', '=', 'departamento.id')
            ->where('departamento_prog_d_form.id_prog_form', $carrera->id)
            ->select('departamento.nombre')
            ->first();

        return [
            'nombre' => $item->profesor->nombre,
            'apellidos' => $item->profesor->apellidos,
            'catDocente' => $item->profesor->catDocente->nombre ?? '',
            'catCientifica' => $item->profesor->catCientifica->nombre ?? '',
            'departamento' => $departamento->nombre ?? '',
            'carrera' => $carrera->nombre ?? '',
            'anio' => $anio->identificador ?? ''
        ];
    });
}

public function getDataResolucion()
{
    $ppa = PPA::with([
        'profesor.catDocente',
        'profesor.catCientifica'
    ])->get();

    return $ppa->map(function ($item) {

        $anio = AnoAcademico::find($item->id_a_academico);
        $carrera = $anio
            ? ProgFormacion::find($anio->id_prog_form)
            : null;

        $departamento = DB::table('departamento_prog_d_form')
            ->join('departamento', 'departamento_prog_d_form.id_departamento', '=', 'departamento.id')
            ->where('departamento_prog_d_form.id_prog_form', $carrera->id ?? 0)
            ->select('departamento.nombre')
            ->first();

        return [
            'nombre' => $item->profesor->nombre . ' ' . $item->profesor->apellidos,
            'carrera' => $carrera->nombre ?? '',
            'anio' => $anio->identificador ?? '',
            'catDocente' => $item->profesor->catDocente->nombre ?? '',
            'catCientifica' => $item->profesor->catCientifica->nombre ?? '',
            'departamento' => $departamento->nombre ?? ''
        ];
    });
}


public function exportResolucionPDF()
{
    Carbon::setLocale('es');
    $fecha = Carbon::now();

    $dia = $fecha->day;
    $mes = $fecha->translatedFormat('F');
    $anio = $fecha->year;
    $revolucion = $anio - 1958;

    // 🔹 DECANO (igual que ya te funciona)
    $decano = Decano::where('id_facultad', 1)->first();
    $profesor = $decano ? Profesor::find($decano->id_profesor) : null;

    $nombreDecano = $profesor
        ? $profesor->nombre . ' ' . $profesor->apellidos
        : '';

    // 🔥 NUEVO: HISTORIAL FILTRADO POR AÑO
    $historial = PpaHistorial::whereYear('fecha_accion', $anio)
        ->with(['profesor.catDocente', 'profesor.catCientifica'])
        ->get();

    // 🔥 SEPARAR ACCIONES
    $ratificados = $historial->where('accion', 'ratificado');
    $desnombrados = $historial->where('accion', 'desnombrado');
    $designados = $historial->where('accion', 'designado');

    // 🔥 MAPEAR (MISMO FORMATO QUE YA USABAS)
    $mapear = function ($items) {
        return $items->map(function ($item) {

            $anio = \App\Models\AnoAcademico::find($item->id_a_academico);
            $carrera = $anio
                ? \App\Models\ProgFormacion::find($anio->id_prog_form)
                : null;

            return [
                'carrera' => $carrera->nombre ?? '',
                'anio' => $anio->identificador ?? '',
                'nombre' => $item->profesor->nombre . ' ' . $item->profesor->apellidos,
                'catDocente' => $item->profesor->catDocente->nombre ?? '',
                'catCientifica' => $item->profesor->catCientifica->nombre ?? '',
            ];
        });
    };

    $ratificados = $mapear($ratificados);
    $desnombrados = $mapear($desnombrados);
    $designados = $mapear($designados);

    // 🔴 IMPORTANTE: quitamos $data, ahora mandamos listas separadas
    $pdf = Pdf::loadView('resolucion', compact(
        'ratificados',
        'desnombrados',
        'designados',
        'dia',
        'mes',
        'anio',
        'revolucion',
        'nombreDecano'
    ));

    return $pdf->download('resolucion.pdf');
}


public function exportResolucionWord()
{
    Carbon::setLocale('es');
    $fecha = Carbon::now();

    $dia = $fecha->day;
    $mes = $fecha->translatedFormat('F');
    $anio = $fecha->year;
    $revolucion = $anio - 1958;

    // 🔹 DECANO
    $decano = Decano::where('id_facultad', 1)->first();
    $profesor = $decano ? Profesor::find($decano->id_profesor) : null;

    $nombreDecano = $profesor
        ? $profesor->nombre . ' ' . $profesor->apellidos
        : '';

    // 🔥 HISTORIAL
    $historial = PpaHistorial::whereYear('fecha_accion', $anio)
        ->with(['profesor.catDocente', 'profesor.catCientifica'])
        ->get();

    $ratificados = $historial->where('accion', 'ratificado');
    $desnombrados = $historial->where('accion', 'desnombrado');
    $designados = $historial->where('accion', 'designado');

    // 🔥 MAPEAR (NO TOCAR)
    $mapear = function ($items) {
        return $items->map(function ($item) {

            $anio = \App\Models\AnoAcademico::find($item->id_a_academico);
            $carrera = $anio
                ? \App\Models\ProgFormacion::find($anio->id_prog_form)
                : null;

            return [
                'carrera' => $carrera->nombre ?? '',
                'anio' => $anio->identificador ?? '',
                'nombre' => $item->profesor->nombre . ' ' . $item->profesor->apellidos,
                'catDocente' => $item->profesor->catDocente->nombre ?? '',
                'catCientifica' => $item->profesor->catCientifica->nombre ?? '',
            ];
        });
    };

    $ratificados = $mapear($ratificados);
    $desnombrados = $mapear($desnombrados);
    $designados = $mapear($designados);

    // 🔥 CREAR WORD
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    // ============================
    // ✅ HEADER BIEN HECHO (CLAVE)
    // ============================

    $table = $section->addTable();

    $table->addRow();

    // Logo izquierdo
    $table->addCell(2000)->addImage(
        public_path('images/logo_izq.png'),
        ['width' => 60]
    );

    // Texto central
    $cellText = $table->addCell(8000, ['valign' => 'center']);

$textrun = $cellText->addTextRun([
    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    'spaceBefore' => 300 // 👈 ESTE es el que baja TODO el bloque
]);
    $cellText->addText(
    'UNIVERSIDAD CENTRAL “MARTA ABREU” DE LAS VILLAS',
    ['bold' => false, 'name' => 'Arial', 'size' => 10], // 👈 sin negrita + más pequeño
    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
);

    $cellText->addText(
        'FACULTAD DE MATEMÁTICA, FÍSICA Y COMPUTACIÓN',
        ['bold' => true, 'name' => 'Arial', 'size' => 10],
        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
    );

    // Logo derecho
    $table->addCell(2000)->addImage(
        public_path('images/logo_der.png'),
        ['width' => 60]
    );

    // Línea


    // ============================
    // ✅ TU HTML (NO LO ROMPEMOS)
    // ============================

    $html = view('resolucion_word', compact(
        'ratificados',
        'desnombrados',
        'designados',
        'dia',
        'mes',
        'anio',
        'revolucion',
        'nombreDecano'
    ))->render();

    // limpiar etiquetas que rompen PhpWord
    $html = preg_replace('/<!DOCTYPE.*?>/', '', $html);
    $html = str_replace(['<html>', '</html>', '<body>', '</body>'], '', $html);

   // ============================
// PARTIR HTML
// ============================

$partes1 = explode('__TABLA_RATIFICADOS__', $html);

// PRIMER BLOQUE (ANTES DE PRIMERO TABLA)
Html::addHtml($section, $partes1[0], false, false);

// ============================
// TABLA RATIFICADOS
// ============================

$table = $section->addTable([
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 50
]);

$table->addRow();
$table->addCell(3500)->addText('Carrera', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(1200)->addText('Año', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(5000)->addText('Nombre', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(3500)->addText('Cat. Docente', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(3500)->addText('Cat. Científica', ['bold' => true, 'name' => 'Arial', 'size' => 12]);

foreach ($ratificados as $item) {
    $table->addRow();

    $table->addCell(3500)->addText($item['carrera'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(1200)->addText($item['anio'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(5000)->addText($item['nombre'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(3500)->addText($item['catDocente'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(3500)->addText($item['catCientifica'], ['name' => 'Arial', 'size' => 12]);
}

// ============================
// SEGUNDA PARTE
// ============================

$partes2 = explode('__TABLA_DESNOMBRADOS__', $partes1[1]);

Html::addHtml($section, $partes2[0]);

// ============================
// TABLA DESNOMBRADOS ✅
// ============================

$table = $section->addTable([
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 50
]);

$table->addRow();
$table->addCell(3500)->addText('Carrera', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(1200)->addText('Año', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(5000)->addText('Nombre', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(3500)->addText('Cat. Docente', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(3500)->addText('Cat. Científica', ['bold' => true, 'name' => 'Arial', 'size' => 12]);

foreach ($desnombrados as $item) {
    $table->addRow();

    $table->addCell(3500)->addText($item['carrera'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(1200)->addText($item['anio'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(5000)->addText($item['nombre'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(3500)->addText($item['catDocente'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(3500)->addText($item['catCientifica'], ['name' => 'Arial', 'size' => 12]);
}

$partes3 = explode('__TABLA_DESIGNADOS__', $partes2[1]);

Html::addHtml($section, $partes3[0]);

// ============================
// TABLA DESIGNADOS
// ============================

$table = $section->addTable([
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 50
]);

$table->addRow();
$table->addCell(3500)->addText('Carrera', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(1200)->addText('Año', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(5000)->addText('Nombre', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(3500)->addText('Cat. Docente', ['bold' => true, 'name' => 'Arial', 'size' => 12]);
$table->addCell(3500)->addText('Cat. Científica', ['bold' => true, 'name' => 'Arial', 'size' => 12]);

foreach ($designados as $item) {
    $table->addRow();

    $table->addCell(3500)->addText($item['carrera'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(1200)->addText($item['anio'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(5000)->addText($item['nombre'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(3500)->addText($item['catDocente'], ['name' => 'Arial', 'size' => 12]);
    $table->addCell(3500)->addText($item['catCientifica'], ['name' => 'Arial', 'size' => 12]);
}
Html::addHtml($section, $partes3[1]);
    // ============================
    // DESCARGA
    // ============================

    $file = storage_path('resolucion.docx');

    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($file);

    return response()->download($file)->deleteFileAfterSend();
}
}
