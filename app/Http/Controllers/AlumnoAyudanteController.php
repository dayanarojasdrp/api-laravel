<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AlumnoAyudante;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use App\Models\Decano;
use App\Models\Profesor;
use Carbon\Carbon;
use App\Models\EstudianteGrupo;
use App\Models\Grupo;
use App\Models\AnoGrupo;
use App\Models\AnoAcademico;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\LogController;
class AlumnoAyudanteController extends Controller
{
    // 🔹 listar
    public function index()
    {
        return AlumnoAyudante::with('estudiante')->get();
    }

    // =====================================
    // 🔥 DESIGNAR
    // =====================================
    public function designar(Request $request)
    {
        $request->validate([
            'id_estudiante' => 'required|exists:estudiantes,id',
            'nombre_tutor' => 'required|string',
            'etapa' => 'required|string'
        ]);

        // 🔥 cerrar cualquier registro activo
        AlumnoAyudante::where('id_estudiante', $request->id_estudiante)
            ->where('habilitado', true)
            ->update([
                'habilitado' => false,
                'fecha_fin' => now()
            ]);

        // 🔥 crear nuevo
       $aa = AlumnoAyudante::create([
    'id_estudiante' => $request->id_estudiante,
    'nombre_tutor' => $request->nombre_tutor,
    'etapa' => $request->etapa,
    'fecha_inicio' => now(),
    'fecha_fin' => null,
     'tipo' => 'designado',
    'habilitado' => true
]);
$usuario = $request->header('X-User') ?? 'desconocido';
// 🔥 LOG ANTES DEL RETURN
$estudiante = $aa->estudiante;

\App\Http\Controllers\LogController::registrar(
    $usuario,
    'designar_aa',
    'Se designó a ' . $estudiante->nombre . ' ' . $estudiante->apellidos .
    ' como Alumno Ayudante (Tutor: ' . $aa->nombre_tutor .
    ', Etapa: ' . $aa->etapa . ')'
);

return $aa;
    }

    // =====================================
    // 🔥 RATIFICAR
    // =====================================
public function ratificar(Request $request, $id)
{
    $registro = AlumnoAyudante::findOrFail($id);

    if (!$registro->habilitado) {
        return response()->json([
            'error' => 'No se puede ratificar un registro inactivo'
        ], 400);
    }

    // 🔥 cerrar el actual
    $registro->update([
        'habilitado' => false,
        'fecha_fin' => now()
    ]);

    // 🔥 crear nuevo registro
    $nuevo = AlumnoAyudante::create([
        'id_estudiante' => $registro->id_estudiante,
        'nombre_tutor' => $registro->nombre_tutor,
        'etapa' => $registro->etapa,
        'fecha_inicio' => now(),
        'fecha_fin' => null,
        'habilitado' => true,
        'tipo' => 'ratificado'
    ]);

    // 🔥 obtener estudiante
    $estudiante = $nuevo->estudiante;

    // 🔥 usuario (igual que designar)
    $usuario = $request->header('X-User') ?? 'desconocido';

    // 🔥 log
    LogController::registrar(
        $usuario,
        'ratificar_aa',
        'Se ratificó a ' . $estudiante->nombre . ' ' . $estudiante->apellidos . ' como Alumno Ayudante'
    );

    return response()->json([
        'message' => 'Ratificado',
        'data' => $nuevo
    ]);
}

    // =====================================
    // 🔥 DESNOMBRAR
    // =====================================
public function desnombrar(Request $request, $id)
{
    $registro = AlumnoAyudante::findOrFail($id);

    if (!$registro->habilitado) {
        return response()->json([
            'error' => 'Ya está desnombrado'
        ], 400);
    }

    // 🔥 cerrar actual
    $registro->update([
        'habilitado' => false,
        'fecha_fin' => now()
    ]);

    // 🔥 crear evento de desnombramiento
    $nuevo = AlumnoAyudante::create([
        'id_estudiante' => $registro->id_estudiante,
        'nombre_tutor' => $registro->nombre_tutor,
        'etapa' => $registro->etapa,
        'fecha_inicio' => now(),
        'fecha_fin' => now(),
        'habilitado' => false,
        'tipo' => 'desnombrado'
    ]);

    // 🔥 obtener estudiante
    $estudiante = $nuevo->estudiante;

    // 🔥 usuario (igual que designar)
    $usuario = $request->header('X-User') ?? 'desconocido';

    // 🔥 log
    LogController::registrar(
        $usuario,
        'desnombrar_aa',
        'Se desnombró a ' . $estudiante->nombre . ' ' . $estudiante->apellidos . ' como Alumno Ayudante'
    );

    return response()->json([
        'message' => 'Desnombrado',
        'data' => $nuevo
    ]);
}

    // =====================================
    // 🔥 ACTUAL POR ESTUDIANTE
    // =====================================
    public function actual($estudianteId)
    {
        return AlumnoAyudante::where('id_estudiante', $estudianteId)
            ->where('habilitado', true)
            ->first();
    }

    // =====================================
    // 🔥 HISTORIAL
    // =====================================
    public function historial($estudianteId)
    {
        return AlumnoAyudante::where('id_estudiante', $estudianteId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    }


public function activos()
{
    return DB::table('alumno_ayudante as aa')
        ->join('estudiantes as e', 'aa.id_estudiante', '=', 'e.id')

        // estudiante → grupo
        ->leftJoin('estudiante_grupo as eg', 'e.id', '=', 'eg.estudiante_id')

        // grupo
        ->leftJoin('grupos as g', 'eg.grupo_id', '=', 'g.id')

        // 🔥 NUEVO: tabla intermedia correcta
        ->leftJoin('ano_grupo as ag', 'g.id', '=', 'ag.grupo_id')

        // año académico
        ->leftJoin('a_academico as a', 'ag.ano_academico_id', '=', 'a.id')

        ->where('aa.habilitado', true)

        ->select(
            'aa.id',
            'aa.id_estudiante',

            'e.nombre',
            'e.apellidos',
            'e.numero_carnet',

            DB::raw("CONCAT(e.nombre, ' ', e.apellidos) as nombre_completo"),

            'aa.nombre_tutor as tutor',
            'aa.etapa',

            // 🔥 esto ahora sí funciona
            'a.identificador as ano_academico'
        )
        ->get();
}


public function aaPdf()
{
    $facultadId = $this->documentFacultyId();

    if (!$facultadId) {
        return response()->json([
            'error' => 'Debe enviar X-Facultad, facultad_id o id_facultad para generar la resolución.'
        ], 422);
    }

    $anioActual = date('Y');

$datos = AlumnoAyudante::with('estudiante')
    ->whereYear('fecha_inicio', $anioActual)
    ->get();
    $designados = $datos->where('tipo', 'designado');
$ratificados = $datos->where('tipo', 'ratificado');
$desnombrados = $datos->where('tipo', 'desnombrado');

 $decano = Decano::with('profesor')
    ->where('id_facultad', $facultadId)
    ->first();

$nombreDecano = $decano && $decano->profesor
    ? $decano->profesor->nombre . ' ' . $decano->profesor->apellidos
    : '';
 $fecha = Carbon::now();
 $dia = $fecha->day;
    $mes = $fecha->translatedFormat('F');
    $ano = $fecha->year;
    $revolucion = $ano - 1958;
    $nombreFacultad = $this->documentFacultyName($facultadId);
    $nombreFacultadMayus = $this->documentFacultyNameUpper($facultadId);
$designados = $this->mapAAResolucionPorDepartamento($designados, $facultadId);
$ratificados = $this->mapAAResolucionPorDepartamento($ratificados, $facultadId);
$desnombrados = $this->mapAAResolucionPorDepartamento($desnombrados, $facultadId);

    // 🔥 AQUÍ EL FIX
   $anio = date('Y');

$pdf = Pdf::loadView('aa_pdf', compact(
    'designados',
    'ratificados',
    'desnombrados',
    'anio',
    'dia',
        'mes',
       'ano',
        'revolucion',
    'nombreDecano',
    'nombreFacultad',
    'nombreFacultadMayus'
));

    // 🔥 fecha completa
$fechaTexto = $fecha->format('d-m-Y_H-i-s');

// 🔥 nombre correcto
$nombreArchivo = $this->documentFileName('Resolucion_AA', 'pdf');

// 🔥 asegurar carpeta
$directorio = storage_path('app/public/documentos');
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

// 🔥 rutas
$ruta = "documentos/{$nombreArchivo}";
$rutaCompleta = storage_path("app/public/{$ruta}");

// 🔥 guardar archivo
file_put_contents($rutaCompleta, $pdf->output());

// 🔥 guardar en BD
\App\Models\Documento::create([
    'nombre' => "Resolución AA {$fechaTexto}",
    'tipo' => 'aa',
    'tipo_documento' => 'resolucion',
    'periodo' => $ano, // 👈 usa tu variable ya calculada
    'ruta' => $ruta,
    'facultad_id' => $this->documentFacultyId(),
]);
$this->logDocumentGenerated('Resolución AA', $fechaTexto);

// 🔥 descargar
return response()->download($rutaCompleta, $nombreArchivo);
}


public function aaWord()
{
    $facultadId = $this->documentFacultyId();

    if (!$facultadId) {
        return response()->json([
            'error' => 'Debe enviar X-Facultad, facultad_id o id_facultad para generar la resolución.'
        ], 422);
    }

    // =====================================
    // 🔥 FECHA (igual que PDF)
    // =====================================
    Carbon::setLocale('es');
    $fecha = Carbon::now();

    $dia = $fecha->day;
    $mes = $fecha->translatedFormat('F');
    $anio = $fecha->year;
    $revolucion = $anio - 1958;
    $nombreFacultad = $this->documentFacultyName($facultadId);
    $nombreFacultadMayus = $this->documentFacultyNameUpper($facultadId);

    // =====================================
    // 🔥 DECANO (igual que PDF)
    // =====================================
    $decano = Decano::with('profesor')
        ->where('id_facultad', $facultadId)
        ->first();

    $nombreDecano = $decano && $decano->profesor
        ? $decano->profesor->nombre . ' ' . $decano->profesor->apellidos
        : '';

    // =====================================
    // 🔥 DATOS (SIN FILTRAR POR habilitado)
    // =====================================
    $anioActual = date('Y');

$datos = AlumnoAyudante::with('estudiante')
    ->whereYear('fecha_inicio', $anioActual)
    ->get();

    $designados = $datos->where('tipo', 'designado');
    $ratificados = $datos->where('tipo', 'ratificado');
    $desnombrados = $datos->where('tipo', 'desnombrado');

    $designados = $this->mapAAResolucionPorDepartamento($designados, $facultadId);
    $ratificados = $this->mapAAResolucionPorDepartamento($ratificados, $facultadId);
    $desnombrados = $this->mapAAResolucionPorDepartamento($desnombrados, $facultadId);

    // =====================================
    // 🔥 RENDERIZAR BLADE (IGUAL QUE PDF)
    // =====================================
    $html = view('aa_word', compact(
        'anio',
        'dia',
        'mes',
        'revolucion',
        'nombreDecano',
        'nombreFacultad',
        'nombreFacultadMayus'
    ))->render();

    // =====================================
    // 🔥 CREAR WORD
    // =====================================
    $phpWord = new PhpWord();
    $section = $phpWord->addSection([
        'marginLeft' => 720,
        'marginRight' => 720,
    ]);
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
        $nombreFacultadMayus,
        ['bold' => true, 'name' => 'Arial', 'size' => 10],
        ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
    );

    // Logo derecho
    $table->addCell(2000)->addImage(
        public_path('images/logo_der.png'),
        ['width' => 60]
    );

    $addAAResolucionTables = function ($grupos) use ($section) {
        foreach ($grupos as $grupo) {
            $section->addText(
                'Departamento Docente: '.$grupo['departamento'],
                ['bold' => true, 'name' => 'Arial', 'size' => 10]
            );

            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 50
            ]);

            $table->addRow();
            $table->addCell(500)->addText('N°', ['bold' => true, 'name' => 'Arial', 'size' => 9]);
            $table->addCell(1600)->addText('C. DE IDENTIDAD', ['bold' => true, 'name' => 'Arial', 'size' => 9]);
            $table->addCell(2700)->addText('NOMBRES Y APELLIDOS', ['bold' => true, 'name' => 'Arial', 'size' => 9]);
            $table->addCell(800)->addText('AÑO', ['bold' => true, 'name' => 'Arial', 'size' => 9]);
            $table->addCell(2700)->addText('TUTOR', ['bold' => true, 'name' => 'Arial', 'size' => 9]);
            $table->addCell(900)->addText('ETAPA', ['bold' => true, 'name' => 'Arial', 'size' => 9]);

            foreach ($grupo['items'] as $fila) {
                $table->addRow();
                $table->addCell(500)->addText($fila['no'], ['name' => 'Arial', 'size' => 9]);
                $table->addCell(1600)->addText($fila['carnet'], ['name' => 'Arial', 'size' => 9]);
                $table->addCell(2700)->addText($fila['nombre'], ['name' => 'Arial', 'size' => 9]);
                $table->addCell(800)->addText($fila['anio'], ['name' => 'Arial', 'size' => 9]);
                $table->addCell(2700)->addText($fila['tutor'], ['name' => 'Arial', 'size' => 9]);
                $table->addCell(900)->addText($fila['etapa'], ['name' => 'Arial', 'size' => 9]);
            }

            $section->addTextBreak();
        }
    };

    $partes1 = explode('__TABLA_DESIGNADOS__', $html);

    Html::addHtml($section, $partes1[0], false, false);

    if (isset($partes1[1])) {
        $addAAResolucionTables($designados);

        $partes2 = explode('__TABLA_DESNOMBRADOS__', $partes1[1]);

        Html::addHtml($section, $partes2[0], false, false);

        if (isset($partes2[1])) {
            $addAAResolucionTables($desnombrados);
            Html::addHtml($section, $partes2[1], false, false);
        }
    }

    // =====================================
    // 🔥 DESCARGA
    // =====================================
    // 🔥 fecha
$fecha = now();
$fechaTexto = $fecha->format('d-m-Y_H-i-s');

// 🔥 nombre correcto
$nombreArchivo = $this->documentFileName('Resolucion_AA', 'docx');

// 🔥 asegurar carpeta
$directorio = storage_path('app/public/documentos');
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

// 🔥 rutas
$ruta = "documentos/{$nombreArchivo}";
$rutaCompleta = storage_path("app/public/{$ruta}");

// 🔥 guardar archivo REAL
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save($rutaCompleta);

// 🔥 guardar en BD
\App\Models\Documento::create([
    'nombre' => "Resolución AA {$fechaTexto}",
    'tipo' => 'aa',
    'tipo_documento' => 'resolucion',
    'periodo' => $fecha->year,
    'ruta' => $ruta,
    'facultad_id' => $this->documentFacultyId(),
]);
$this->logDocumentGenerated('Resolución AA', $fechaTexto);

// 🔥 descargar
return response()->download($rutaCompleta, $nombreArchivo);
}
public function exportPDF()
{
    $data = $this->getAAData();

    $pdf = Pdf::loadView('exports.aa', ['data' => $data]);

   // 🔥 fecha completa
$fecha = now();
$fechaTexto = $fecha->format('d-m-Y_H-i-s');

// 🔥 nombre dinámico
$nombreArchivo = $this->documentFileName('Listado_AA', 'pdf');

// 🔥 asegurar carpeta
$directorio = storage_path('app/public/documentos');
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

// 🔥 rutas
$ruta = "documentos/{$nombreArchivo}";
$rutaCompleta = storage_path("app/public/{$ruta}");

// 🔥 guardar archivo
file_put_contents($rutaCompleta, $pdf->output());

// 🔥 guardar en BD
\App\Models\Documento::create([
    'nombre' => "Listado AA {$fechaTexto}",
    'tipo' => 'aa',
    'tipo_documento' => 'listado',
    'periodo' => $fecha->year,
    'ruta' => $ruta,
    'facultad_id' => $this->documentFacultyId(),
]);
$this->logDocumentGenerated('Listado AA', $fechaTexto);

// 🔥 descargar
return response()->download($rutaCompleta, $nombreArchivo);
}
public function exportWord()
{
    $data = $this->getAAData();

    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $section = $phpWord->addSection([
        'marginLeft' => 720,
        'marginRight' => 720,
    ]);

    // 🔹 Título
    $section->addText(
        'Listado de Alumnos Ayudantes',
        ['name' => 'Arial', 'size' => 14, 'bold' => true]
    );

    // 🔹 Tabla
    $table = $section->addTable([
        'borderSize' => 6,
        'borderColor' => '000000',
        'cellMargin' => 50
    ]);

    // 🔹 HEADER
    $table->addRow();

    $table->addCell(1700)->addText('Carnet', ['bold' => true, 'name' => 'Arial', 'size' => 10]);
    $table->addCell(3100)->addText('Nombre', ['bold' => true, 'name' => 'Arial', 'size' => 10]);
    $table->addCell(1200)->addText('Año Académico', ['bold' => true, 'name' => 'Arial', 'size' => 10]);
    $table->addCell(2600)->addText('Tutor', ['bold' => true, 'name' => 'Arial', 'size' => 10]);
    $table->addCell(800)->addText('Etapa', ['bold' => true, 'name' => 'Arial', 'size' => 10]);

    // 🔹 DATA
    foreach ($data as $item) {
        $table->addRow();

        $table->addCell(1700)->addText($item['carnet'], ['name' => 'Arial', 'size' => 10]);

        $table->addCell(3100)->addText(
            $item['nombre'],
            ['name' => 'Arial', 'size' => 10]
        );

        $table->addCell(1200)->addText($item['anio'], ['name' => 'Arial', 'size' => 10]);

        $table->addCell(2600)->addText($item['tutor'], ['name' => 'Arial', 'size' => 10]);

        $table->addCell(800)->addText($item['etapa'], ['name' => 'Arial', 'size' => 10]);
    }

   // 🔥 fecha completa
$fecha = now();
$fechaTexto = $fecha->format('d-m-Y_H-i-s');

// 🔥 nombre dinámico
$nombreArchivo = $this->documentFileName('Listado_AA', 'docx');

// 🔥 asegurar carpeta
$directorio = storage_path('app/public/documentos');
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

// 🔥 rutas
$ruta = "documentos/{$nombreArchivo}";
$rutaCompleta = storage_path("app/public/{$ruta}");

// 🔥 guardar archivo
\PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007')->save($rutaCompleta);

// 🔥 guardar en BD
\App\Models\Documento::create([
    'nombre' => "Listado AA {$fechaTexto}",
    'tipo' => 'aa',
    'tipo_documento' => 'listado',
    'periodo' => $fecha->year,
    'ruta' => $ruta,
    'facultad_id' => $this->documentFacultyId(),
]);
$this->logDocumentGenerated('Listado AA', $fechaTexto);

// 🔥 descargar
return response()->download($rutaCompleta, $nombreArchivo);
}
private function getAAData()
{
    $facultadId = $this->documentFacultyId();
    $departamentoId = $this->documentDepartmentId();

    $aa = \App\Models\AlumnoAyudante::with('estudiante')
        ->where('habilitado', true)
        ->get();

    return $aa->map(function ($item) use ($facultadId, $departamentoId) {
        $ubicacion = $this->ubicacionAcademicaEstudianteExport($item->id_estudiante, $facultadId, $departamentoId);

        if (($facultadId || $departamentoId) && !$ubicacion) {
            return null;
        }

        return [
            'carnet' => $item->estudiante->numero_carnet,
            'nombre' => $item->estudiante->nombre . ' ' . $item->estudiante->apellidos,
            'anio' => $ubicacion->anio ?? 'N/A',
            'tutor' => $item->nombre_tutor,
            'etapa' => $this->formatEtapaDocumento($item->etapa)
        ];
    })->filter()->values();
}
private function formatEtapaDocumento($etapa): string
{
    $valor = trim((string) $etapa);
    $normalizado = mb_strtolower($valor);

    $mapa = [
        '1' => '|',
        'etapa 1' => '|',
        '2' => '||',
        'etapa 2' => '||',
        '3' => '|||',
        'etapa 3' => '|||',
    ];

    return $mapa[$normalizado] ?? $valor;
}
private function mapAAResolucionPorDepartamento($coleccion, int $facultadId)
{
    return $coleccion->values()
        ->map(function ($aa) use ($facultadId) {
            $ubicacion = $this->ubicacionAcademicaEstudiante($aa->id_estudiante, $facultadId);

            if (!$ubicacion || !$aa->estudiante) {
                return null;
            }

            return [
                'departamento_id' => $ubicacion->departamento_id,
                'departamento' => $ubicacion->departamento,
                'carnet' => $aa->estudiante->numero_carnet,
                'nombre' => $aa->estudiante->nombre . ' ' . $aa->estudiante->apellidos,
                'anio' => $ubicacion->anio,
                'tutor' => $aa->nombre_tutor,
                'etapa' => $this->formatEtapaDocumento($aa->etapa),
            ];
        })
        ->filter()
        ->groupBy('departamento_id')
        ->map(function ($items) {
            $items = $items->values();

            return [
                'departamento' => $items->first()['departamento'],
                'items' => $items->map(function ($item, $index) {
                    unset($item['departamento_id'], $item['departamento']);
                    $item['no'] = $index + 1;

                    return $item;
                })->values(),
            ];
        })
        ->values();
}

private function ubicacionAcademicaEstudiante(int $estudianteId, int $facultadId)
{
    return DB::table('estudiante_grupo as eg')
        ->join('ano_grupo as ag', 'eg.grupo_id', '=', 'ag.grupo_id')
        ->join('a_academico as aa', 'ag.ano_academico_id', '=', 'aa.id')
        ->join('departamento_prog_d_form as dpf', 'aa.id_prog_form', '=', 'dpf.id_prog_form')
        ->join('departamento as d', 'dpf.id_departamento', '=', 'd.id')
        ->join('facultad_departamento as fd', 'd.id', '=', 'fd.id_departamento')
        ->where('eg.estudiante_id', $estudianteId)
        ->where('fd.id_facultad', $facultadId)
        ->orderByDesc('eg.fecha')
        ->select(
            'd.id as departamento_id',
            'd.nombre as departamento',
            'aa.identificador as anio'
        )
        ->first();
}

private function ubicacionAcademicaEstudianteExport(int $estudianteId, ?int $facultadId, ?int $departamentoId)
{
    $query = DB::table('estudiante_grupo as eg')
        ->join('ano_grupo as ag', 'eg.grupo_id', '=', 'ag.grupo_id')
        ->join('a_academico as aa', 'ag.ano_academico_id', '=', 'aa.id')
        ->join('departamento_prog_d_form as dpf', 'aa.id_prog_form', '=', 'dpf.id_prog_form')
        ->join('departamento as d', 'dpf.id_departamento', '=', 'd.id')
        ->join('facultad_departamento as fd', 'd.id', '=', 'fd.id_departamento')
        ->where('eg.estudiante_id', $estudianteId);

    if ($departamentoId) {
        $query->where('d.id', $departamentoId);
    } elseif ($facultadId) {
        $query->where('fd.id_facultad', $facultadId);
    }

    return $query
        ->orderByDesc('eg.fecha')
        ->select(
            'd.id as departamento_id',
            'd.nombre as departamento',
            'fd.id_facultad',
            'aa.identificador as anio'
        )
        ->first();
}
public function historialAA(Request $request)
{
    $desde = $request->desde;
    $hasta = $request->hasta;

    // 🔥 traer AA en rango de años
    $aa = \App\Models\AlumnoAyudante::with([
            'estudiante',
        ])
        ->whereNotNull('fecha_inicio')
        ->whereYear('fecha_inicio', '>=', $desde)
        ->whereYear('fecha_inicio', '<=', $hasta)
        ->where('tipo', 'designado') // opcional según tu lógica
        ->get()
        ->unique(function ($item) {
            // 🔥 evita repetir mismo estudiante en el mismo año
            return $item->id_estudiante . '-' . date('Y', strtotime($item->fecha_inicio));
        });

    // 🔥 departamentos (igual que hiciste en PPA)
    $miembros = \App\Models\MiembroDepartamento::whereIn(
        'id_profesor',
        $aa->pluck('id_tutor') // 👈 tutor es profesor
    )->get()->keyBy('id_profesor');

    $departamentos = \App\Models\Departamento::whereIn(
        'id',
        $miembros->pluck('id_departamento')
    )->get()->keyBy('id');

    // 🔥 MAP FINAL
    $data = $aa->map(function ($item) use ($miembros, $departamentos) {

        $est = $item->estudiante;
        if (!$est) return null;

        // 🔥 departamento del tutor
        $miembro = $miembros[$item->id_tutor] ?? null;
        $departamento = $miembro
            ? ($departamentos[$miembro->id_departamento]->nombre ?? '')
            : '';

        return [
            'carnet' => $est->numero_carnet ?? '',
            'nombre' => trim(($est->nombre ?? '') . ' ' . ($est->apellidos ?? '')),
            'tutor' => $item->nombre_tutor ?? '',
            'departamento' => $departamento,
            'anio' => date('Y', strtotime($item->fecha_inicio)) // 🔥 AÑO REAL
        ];
    })->filter();

    // 🔥 validación
    if ($data->isEmpty()) {
        return response()->json(['error' => 'No hay datos'], 400);
    }

    // 🔥 PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'exports.historial_aa',
        [
            'data' => $data,
            'desde' => $desde,
            'hasta' => $hasta
        ]
    );

    $nombreArchivo = $this->documentFileName("Historial_AA_{$desde}_{$hasta}", 'pdf');
    $ruta = "documentos/{$nombreArchivo}";

    // 🔥 guardar
    \Illuminate\Support\Facades\Storage::disk('public')->put($ruta, $pdf->output());

    // 🔥 guardar en BD
    \App\Models\Documento::create([
        'nombre' => "Historial AA {$desde}-{$hasta}",
        'tipo' => 'aa',
        'tipo_documento' => 'historial',
        'periodo' => $hasta,
        'ruta' => $ruta,
        'facultad_id' => $this->documentFacultyId(),
    ]);
    $this->logDocumentGenerated('Historial AA', "{$desde}-{$hasta}");

    return $pdf->download($nombreArchivo);
}
}
