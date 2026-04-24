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
    $datos = AlumnoAyudante::with('estudiante')->get();
    $designados = $datos->where('tipo', 'designado');
$ratificados = $datos->where('tipo', 'ratificado');
$desnombrados = $datos->where('tipo', 'desnombrado');

 $decano = Decano::with('profesor')
    ->where('id_facultad', 1)
    ->first();

$nombreDecano = $decano && $decano->profesor
    ? $decano->profesor->nombre . ' ' . $decano->profesor->apellidos
    : '';
 $fecha = Carbon::now();
 $dia = $fecha->day;
    $mes = $fecha->translatedFormat('F');
    $ano = $fecha->year;
    $revolucion = $ano - 1958;
  function mapear($coleccion) {
    return $coleccion->values()->map(function ($aa, $index) {
         $estGrupo = EstudianteGrupo::where('estudiante_id', $aa->id_estudiante)->first();

        $anoNombre = 'N/A';

        if ($estGrupo) {
            $grupo = Grupo::find($estGrupo->grupo_id);

            if ($grupo) {
                $anoGrupo = AnoGrupo::where('grupo_id', $grupo->id)->first();

                if ($anoGrupo) {
                    $ano = AnoAcademico::find($anoGrupo->ano_academico_id);

                    if ($ano) {
                        $anoNombre = $ano->identificador; // 👈 ejemplo: "1ro", "2do"
                    }
                }
            }
        }
        return [
            'no' => $index + 1,
            'carnet' => $aa->estudiante->numero_carnet,
            'nombre' => $aa->estudiante->nombre . ' ' . $aa->estudiante->apellidos,
            'anio' => $anoNombre,
            'tutor' => $aa->nombre_tutor,
            'etapa' => $aa->etapa
        ];
    });
}

$designados = mapear($designados);
$ratificados = mapear($ratificados);
$desnombrados = mapear($desnombrados);

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
    'nombreDecano'
));

    return $pdf->download('resolucion_aa.pdf');
}


public function aaWord()
{
    // =====================================
    // 🔥 FECHA (igual que PDF)
    // =====================================
    Carbon::setLocale('es');
    $fecha = Carbon::now();

    $dia = $fecha->day;
    $mes = $fecha->translatedFormat('F');
    $anio = $fecha->year;
    $revolucion = $anio - 1958;

    // =====================================
    // 🔥 DECANO (igual que PDF)
    // =====================================
    $decano = Decano::with('profesor')
        ->where('id_facultad', 1)
        ->first();

    $nombreDecano = $decano && $decano->profesor
        ? $decano->profesor->nombre . ' ' . $decano->profesor->apellidos
        : '';

    // =====================================
    // 🔥 DATOS (SIN FILTRAR POR habilitado)
    // =====================================
    $datos = AlumnoAyudante::with('estudiante')->get();

    $designados = $datos->where('tipo', 'designado');
    $ratificados = $datos->where('tipo', 'ratificado');
    $desnombrados = $datos->where('tipo', 'desnombrado');

    // =====================================
    // 🔥 TABLA (IGUAL QUE PDF)
    // =====================================
  $tabla = function ($coleccion) {

    $html = '
    <table border="1" cellpadding="4" cellspacing="0" width="100%"
    style="border-collapse: collapse; font-family: Arial; font-size: 12pt;">

        <tr>
            <th style="font-family: Arial; font-size: 12pt;">No</th>
            <th style="font-family: Arial; font-size: 12pt;">Carnet</th>
            <th style="font-family: Arial; font-size: 12pt;">Nombre</th>
            <th style="font-family: Arial; font-size: 12pt;">Año Académico</th>
            <th style="font-family: Arial; font-size: 12pt;">Tutor</th>
            <th style="font-family: Arial; font-size: 12pt;">Etapa</th>
        </tr>';

    $i = 1;

    foreach ($coleccion as $aa) {

        $anoNombre = 'N/A';

        $estGrupo = \App\Models\EstudianteGrupo::where('estudiante_id', $aa->id_estudiante)->first();

        if ($estGrupo) {
            $grupo = \App\Models\Grupo::find($estGrupo->grupo_id);

            if ($grupo) {
                $anoGrupo = \App\Models\AnoGrupo::where('grupo_id', $grupo->id)->first();

                if ($anoGrupo) {
                    $ano = \App\Models\AnoAcademico::find($anoGrupo->ano_academico_id);

                    if ($ano) {
                        $anoNombre = $ano->identificador;
                    }
                }
            }
        }

        $html .= '
        <tr>
            <td style="font-family: Arial; font-size: 12pt; text-align:center;">'.$i++.'</td>
            <td style="font-family: Arial; font-size: 12pt; text-align:center;">'.$aa->estudiante->numero_carnet.'</td>
            <td style="font-family: Arial; font-size: 12pt;">'.$aa->estudiante->nombre.' '.$aa->estudiante->apellidos.'</td>
            <td style="font-family: Arial; font-size: 12pt; text-align:center;">'.$anoNombre.'</td>
            <td style="font-family: Arial; font-size: 12pt;">'.$aa->nombre_tutor.'</td>
            <td style="font-family: Arial; font-size: 12pt; text-align:center;">'.$aa->etapa.'</td>
        </tr>';
    }

    $html .= '</table>';

    return $html;
};

    // =====================================
    // 🔥 RENDERIZAR BLADE (IGUAL QUE PDF)
    // =====================================
    $html = view('aa_word', compact(
        'anio',
        'dia',
        'mes',
        'revolucion',
        'nombreDecano'
    ))->render();

    // =====================================
    // 🔥 REEMPLAZAR TABLAS
    // =====================================
    $html = str_replace('__TABLA_RATIFICADOS__', $tabla($ratificados), $html);
    $html = str_replace('__TABLA_DESNOMBRADOS__', $tabla($desnombrados), $html);
    $html = str_replace('__TABLA_DESIGNADOS__', $tabla($designados), $html);

    // =====================================
    // 🔥 CREAR WORD
    // =====================================
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

    Html::addHtml($section, $html);

    // =====================================
    // 🔥 DESCARGA
    // =====================================
    return new StreamedResponse(function () use ($phpWord) {
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
    }, 200, [
        "Content-Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "Content-Disposition" => "attachment;filename=Resolucion_AA.docx",
    ]);
}
}
