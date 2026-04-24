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
        return AlumnoAyudante::create([
            'id_estudiante' => $request->id_estudiante,
            'nombre_tutor' => $request->nombre_tutor,
            'etapa' => $request->etapa,
            'fecha_inicio' => now(),
            'fecha_fin' => null,
            'habilitado' => true,
            'tipo' => 'designado'
        ]);
    }

    // =====================================
    // 🔥 RATIFICAR
    // =====================================
  public function ratificar($id)
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

    // 🔥 crear nuevo registro (ratificación)
    return AlumnoAyudante::create([
        'id_estudiante' => $registro->id_estudiante,
        'nombre_tutor' => $registro->nombre_tutor,
        'etapa' => $registro->etapa,
        'fecha_inicio' => now(),
        'fecha_fin' => null,
        'habilitado' => true,
        'tipo' => 'ratificado'
    ]);
}

    // =====================================
    // 🔥 DESNOMBRAR
    // =====================================
  public function desnombrar($id)
{
    $registro = AlumnoAyudante::findOrFail($id);

    if (!$registro->habilitado) {
        return response()->json(['error' => 'Ya está desnombrado'], 400);
    }

    // cerrar actual
    $registro->update([
        'habilitado' => false,
        'fecha_fin' => now()
    ]);

    // 🔥 crear evento de desnombramiento
    return AlumnoAyudante::create([
        'id_estudiante' => $registro->id_estudiante,
        'nombre_tutor' => $registro->nombre_tutor,
        'etapa' => $registro->etapa,
        'fecha_inicio' => now(),
        'fecha_fin' => now(),
        'habilitado' => false,
        'tipo' => 'desnombrado'
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
   $datos = AlumnoAyudante::with('estudiante')->get();

    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    $section->addText('Resolución de Alumnos Ayudantes', ['bold' => true, 'size' => 16]);

    foreach ($datos as $index => $aa) {

        $ano = 'N/A'; // luego lo conectas igual que en PDF

        $section->addText("No: " . ($index + 1));
        $section->addText("Carnet: " . $aa->estudiante->numero_carnet);
        $section->addText("Nombre: " . $aa->estudiante->nombre . ' ' . $aa->estudiante->apellidos);
        $section->addText("Año académico: " . $ano);
        $section->addText("Tutor: " . $aa->nombre_tutor);
        $section->addText("Etapa: " . $aa->etapa);

        $section->addTextBreak(1);
    }

    $file = storage_path('resolucion_aa.docx');
    $phpWord->save($file);

    return response()->download($file)->deleteFileAfterSend(true);
}
}
