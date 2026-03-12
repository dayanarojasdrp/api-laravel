<?php

namespace App\Http\Controllers\API\indicadores;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use App\Models\TipoIndicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IndicadorDepartamento;
use App\Models\IndicadorAgno;
use App\Models\Universidad;
use App\Models\Asignatura;
use App\Models\Facultad;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\ProgFormacion;
use App\Models\AñoAcademico;
use App\Models\IndicadorAsignatura;
use App\Models\IndicadorFacultad;
use App\Models\IndicadorUniversidad;
use App\Models\IndicadorProgForm;
use Illuminate\Support\Facades\Validator;

class indicadorController extends Controller
{
    public function index() {
        $ind = Indicador::all();
        return response()->json(['res'=> true, 'data'=> $ind, 'status'=> 200], 200);
    }

    public function store(Request $request) {
        $val = Validator::make($request->all(), [
            'nombre' => 'required',
            'tipoDeDato' => 'required|in:numerico,no-numerico',
            'idTipoDeIndicador' => 'required',
            'asociado' => 'required|in:agno,asignatura,departamento,facultad,universidad'
        ]);
        if ($val->fails()) return response()->json(['res'=> false, 'message'=> $val->errors(), 'status'=> 400], 400);
        $tipo = TipoIndicador::find($request->idTipoDeIndicador);
        if (!$tipo) return response()->json(['res'=> false, 'message'=> 'El id del tipo de indicador no es valido', 'status'=> 400], 400);
        $ind = Indicador::create([
            'nombre' => $request->nombre,
            'tipoDeDato' => $request->tipoDeDato,
            'idTipoDeIndicador' => $request->idTipoDeIndicador,
            'asociado' => $request->asociado
        ]);
        if (!$ind) return response()->json(['res'=> false, 'message'=> 'No se pudo crear el indicador', 'status'=> 400], 400);
        return response()->json(['res'=> true, 'message'=> 'Se creo correctamente el indicador', 'status'=> 200], 200);
    }

    public function show($id) {
        $ind = Indicador::find($id);
        if(!$ind) return response()->json(['res'=> false, 'message'=> 'No se encontro el año academico'], 400);
        return response()->json(['res'=> true, 'data'=> $ind], 200);
    }

    public function update(Request $request, $id) {
        $val = Validator::make($request->all(), [
            'tipoDeDato' => 'in:numerico,no-numerico',
            'asociado' => 'in:agno,asignatura,departamento,facultad,universidad'
        ]);
        if ($val->fails()) return response()->json(['res'=> false, 'message'=> $val->errors(), 'status'=> 400], 400);
        $ind = Indicador::find($id);
        if (!$ind) return response()->json(['res'=> false, 'message'=>'No se encontro el indicador', 'status'=> 400], 400);
        if ($request->has('idTipoDeIndicador')) {
            $tipo = TipoIndicador::find($request->idTipoDeIndicador);
            if (!$tipo) return response()->json(['res'=> false, 'message'=> 'El id del tipo de indicador no es valido', 'status'=> 400], 400);
        }
        $ind->update($request->all());
        if (!$ind) return response()->json(['res'=> false, 'message'=>'No se pudo actualizar el indicador', 'status'=> 400], 400);
        return response()->json(['res'=> true, 'message'=> 'Se actualizo correctamente el indicador', 'status'=> 200], 200);
    }

    public function destroy(Request $request, $id) {
        $ind = Indicador::where('id', $id)->first();
        if (!$ind) return response()->json(['res'=> false, 'message'=>'No se encontro el indicador', 'status'=> 400], 400);
        $ind->delete();
        return response()->json(['res'=> true, 'message'=> 'Se elimino correctamente el indicador', 'status'=> 200], 200);
    }
    public function multiAgno($id) {
        $data = DB::table('indicador_agno')->where('idIndicador', '=', $id)->join('curso', 'indicador_agno.idCurso', '=', 'curso.id')
            ->join('a-academico', 'indicador_agno.idAñoAcademico', '=', 'a-academico.id')
            ->join('programa-de-formacion', 'a-academico.id_prog_form', '=', 'programa-de-formacion.id')
            ->select('indicador_agno.valor as valor', 'programa-de-formacion.nombre as carrera',
            'a-academico.identificador as agno', 'curso.curso as curso')->get();
        return response()->json(['res'=> true, 'data'=>$data, 'status'=> 200], 200);
    }
    public function multiAsig($id) {
        $data = DB::table('indicador_asignatura')->where('idIndicador', '=', $id)->join('curso', 'indicador_asignatura.idCurso', '=', 'curso.id')
            ->join('a-academico', 'indicador_asignatura.idAñoAcademico', '=', 'a-academico.id')
            ->join('programa-de-formacion', 'a-academico.id_prog_form', '=', 'programa-de-formacion.id')
            ->join('asignatura', 'indicador_asignatura.idAsignatura', '=', 'asignatura.id')
            ->select('indicador_asignatura.valor as valor', 'programa-de-formacion.nombre as carrera',
            'a-academico.identificador as agno', 'curso.curso as curso', 'asignatura.nombre as asignatura')->get();
        return response()->json(['res'=> true, 'data'=>$data, 'status'=> 200], 200);
    }
    public function multiDept($id) {
    $data = DB::table('indicador_departamento')
        ->where('idIndicador', $id)
        ->join('departamento', 'indicador_departamento.idDepartamento', '=', 'departamento.id')
        ->join('curso', 'indicador_departamento.idCurso', '=', 'curso.id')
        ->select(
            'indicador_departamento.valor',
            'departamento.nombre as departamento',
            'curso.curso'
        )->get();

    return response()->json(['res' => true, 'data' => $data], 200);
}
public function getValores($id, Request $request)
{
    $indicador = Indicador::findOrFail($id);
    $valores = [];

    switch ($indicador->asociado) {
        case 'departamento':
            $valores = Departamento::with(['indicadores' => function($query) use ($id) {
                $query->where('indicador_departamento.idIndicador', $id);
            }])
            ->get()
            ->map(function ($departamento) use ($id, $indicador) {
                $registro = $departamento->indicadores->first();
                return [
                    'id' => $departamento->id,
                    'nombre' => $departamento->nombre,
                    'valor' => $registro ? $registro->valor : ($indicador->tipoDeDato === 'numerico' ? 0 : null),
                    'puedeEditar' => true
                ];
            });
            break;

        case 'carrera':
            $valores = ProgFormacion::with(['indicadores' => function($query) use ($id) {
                $query->where('indicador_carrera.idIndicador', $id);
            }])
            ->get()
            ->map(function ($carrera) use ($id, $indicador) {
                $registro = $carrera->indicadores->first();
                return [
                    'id' => $carrera->id,
                    'nombre' => $carrera->nombre,
                    'valor' => $registro ? $registro->valor : ($indicador->tipoDeDato === 'numerico' ? 0 : null),
                    'puedeEditar' => true
                ];
            });
            break;

        case 'agno':
            $valores = AñoAcademico::with(['indicadores' => function($query) use ($id) {
                $query->where('indicador_agno.idIndicador', $id);
            }])
            ->get()
            ->map(function ($agno) use ($id, $indicador) {
                $registro = $agno->indicadores->first();
                return [
                    'id' => $agno->id,
                    'nombre' => $agno->identificador,
                    'valor' => $registro ? $registro->valor : ($indicador->tipoDeDato === 'numerico' ? 0 : null),
                    'puedeEditar' => true
                ];
            });
            break;

        case 'asignatura':
            $valores = Asignatura::with(['indicadores' => function($query) use ($id) {
                $query->where('indicador_asignatura.idIndicador', $id);
            }])
            ->get()
            ->map(function ($asignatura) use ($id, $indicador) {
                $registro = $asignatura->indicadores->first();
                return [
                    'id' => $asignatura->id,
                    'nombre' => $asignatura->nombre,
                    'valor' => $registro ? $registro->valor : ($indicador->tipoDeDato === 'numerico' ? 0 : null),
                    'puedeEditar' => true
                ];
            });
            break;

        case 'facultad':
            $valores = Facultad::with(['indicadores' => function($query) use ($id) {
                $query->where('indicador_facultad.idIndicador', $id);
            }])
            ->get()
            ->map(function ($facultad) use ($id, $indicador) {
                $registro = $facultad->indicadores->first();
                return [
                    'id' => $facultad->id,
                    'nombre' => $facultad->nombre,
                    'valor' => $registro ? $registro->valor : ($indicador->tipoDeDato === 'numerico' ? 0 : null),
                    'puedeEditar' => true
                ];
            });
            break;

        case 'universidad':
            $valores = Universidad::with(['indicadores' => function($query) use ($id) {
                $query->where('indicador_universidad.idIndicador', $id);
            }])
            ->get()
            ->map(function ($universidad) use ($id, $indicador) {
                $registro = $universidad->indicadores->first();
                return [
                    'id' => $universidad->id,
                    'nombre' => $universidad->nombre,
                    'valor' => $registro ? $registro->valor : ($indicador->tipoDeDato === 'numerico' ? 0 : null),
                    'puedeEditar' => true
                ];
            });
            break;
    }

    return response()->json([
        'res' => true,
        'data' => $valores,
        'nivelAsociado' => $indicador->asociado,
        'tipoDato' => $indicador->tipoDeDato
    ]);
}

public function guardarValorIndicador(Request $request)
{
    $validated = $request->validate([
        'idIndicador' => 'required|exists:indicador,id',
        'idEntidad' => 'required|integer',
        'valor' => 'required',
        'nivelAsociado' => 'required|in:departamento,carrera,agno,asignatura,facultad,universidad'
    ]);

    $indicador = Indicador::find($validated['idIndicador']);
    $ultimoCurso = Curso::latest()->first();

    if (!$ultimoCurso) {
        return response()->json([
            'res' => false,
            'message' => 'No hay cursos disponibles en el sistema'
        ], 400);
    }

    // Validar tipo de dato si es numérico
    if ($indicador->tipoDeDato === 'numerico' && !is_numeric($validated['valor'])) {
        return response()->json([
            'res' => false,
            'message' => 'El valor debe ser numérico para este indicador'
        ], 400);
    }

    // Mapeo entre nivelAsociado y modelo/claves
    $modelos = [
    'departamento' => [IndicadorDepartamento::class, 'idDepartamento'],
    'carrera'      => [IndicadorProgForm::class, 'idProgFormacion'], // 🔄
    'agno'         => [IndicadorAgno::class, 'idAgno'],
    'asignatura'   => [IndicadorAsignatura::class, 'idAsignatura'],
    'facultad'     => [IndicadorFacultad::class, 'idFacultad'],
    'universidad'  => [IndicadorUniversidad::class, 'idUniversidad'],
];


    [$modelo, $claveEntidad] = $modelos[$validated['nivelAsociado']];

    // Buscar si ya existe
    $registroExistente = $modelo::where([
        'idIndicador' => $validated['idIndicador'],
        $claveEntidad => $validated['idEntidad'],
        'idCurso' => $ultimoCurso->id
    ])->first();

    if ($registroExistente) {
        $registroExistente->valor = $validated['valor'];
        $registroExistente->save();
        $registro = $registroExistente;
    } else {
        $registro = $modelo::create([
            'idIndicador' => $validated['idIndicador'],
            $claveEntidad => $validated['idEntidad'],
            'idCurso' => $ultimoCurso->id,
            'valor' => $validated['valor']
        ]);
    }

    if (!$registro) {
        return response()->json([
            'res' => false,
            'message' => 'No se pudo guardar el valor del indicador'
        ], 400);
    }

    return response()->json([
        'res' => true,
        'data' => $registro,
        'message' => 'Valor guardado correctamente'
    ]);
}

}
