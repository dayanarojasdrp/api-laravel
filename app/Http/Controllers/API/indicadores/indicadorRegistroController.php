<?php

namespace App\Http\Controllers\API\indicadores;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\AñoAcademico;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Universidad;
use App\Models\Facultad;
use App\Models\ProgFormacion;
use App\Models\Indicador;
use App\Models\IndicadorAgno;
use App\Models\IndicadorAsignatura;
use App\Models\IndicadorDepartamento;
use App\Models\IndicadorUniversidad;
use App\Models\IndicadorFacultad;
use App\Models\IndicadorProgForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class indicadorRegistroController extends Controller
{
    public function index() {
        return response()->json([
            'res' => true,
            'data' => [
                'a-academico' => IndicadorAgno::all(),
                'asignatura' => IndicadorAsignatura::all(),
                'departamento' => IndicadorDepartamento::all()
            ],
            'status' => 200
        ], 200);
    }

    public function store(Request $request) {
        // Obtener el tipo de indicador
        $ind = Indicador::find($request->idIndicador);
        if (!$ind) {
            return response()->json([
                'res' => false,
                'message' => 'No es valido el id del indicador',
                'status' => 400
            ], 400);
        }

        // Definir reglas según tipo de asociación
        $rules = [
            'valor' => 'required',
            'idIndicador' => 'required',
        ];

        if ($ind->asociado === 'asignatura') {
            $rules['idAsignatura'] = 'required';
            $rules['idCurso'] = 'required';
            $rules['idAñoAcademico'] = 'required';
        }
        elseif ($ind->asociado === 'agno') {
            $rules['idCurso'] = 'required';
            $rules['idAñoAcademico'] = 'required';
        }
        elseif ($ind->asociado === 'departamento') {
            $rules['idDepartamento'] = 'required';
            $rules['idCurso'] = 'required';
        }
        elseif ($ind->asociado === 'universidad') {
    $rules['idUniversidad'] = 'required';
}
elseif ($ind->asociado === 'facultad') {
    $rules['idFacultad'] = 'required';
}
elseif ($ind->asociado === 'progformacion') {
    $rules['idProgFormacion'] = 'required';
}


        $val = Validator::make($request->all(), $rules);
        if ($val->fails()) {
            return response()->json([
                'res' => false,
                'message' => $val->errors(),
                'status' => 400
            ], 400);
        }

        // Validar curso en todos los casos
        $curso = Curso::find($request->idCurso);
        if (!$curso) {
            return response()->json([
                'res' => false,
                'message' => 'No es valido el id del curso',
                'status' => 400
            ], 400);
        }

        // Manejar creación según tipo
        switch ($ind->asociado) {
            case 'asignatura':
                $asig = Asignatura::find($request->idAsignatura);
                if (!$asig) {
                    return response()->json([
                        'res' => false,
                        'message' => 'No es valido el id de la asignatura',
                        'status' => 400
                    ], 400);
                }

                $aA = AñoAcademico::find($request->idAñoAcademico);
                if (!$aA) {
                    return response()->json([
                        'res' => false,
                        'message' => 'No es valido el id del año academico',
                        'status' => 400
                    ], 400);
                }

                $register = IndicadorAsignatura::create($request->all());
                break;

            case 'agno':
                $aA = AñoAcademico::find($request->idAñoAcademico);
                if (!$aA) {
                    return response()->json([
                        'res' => false,
                        'message' => 'No es valido el id del año academico',
                        'status' => 400
                    ], 400);
                }

                $register = IndicadorAgno::create($request->all());
                break;

            case 'departamento':
                $dep = Departamento::find($request->idDepartamento);
                if (!$dep) {
                    return response()->json([
                        'res' => false,
                        'message' => 'No es valido el id del departamento',
                        'status' => 400
                    ], 400);
                }

                $register = IndicadorDepartamento::create([
                    'idDepartamento' => $request->idDepartamento,
                    'idIndicador' => $request->idIndicador,
                    'idCurso' => $request->idCurso,
                    'valor' => $request->valor
                ]);
                break;

                case 'universidad':
    $uni = Universidad::find($request->idUniversidad);
    if (!$uni) {
        return response()->json([
            'res' => false,
            'message' => 'No es válido el id de la universidad',
            'status' => 400
        ], 400);
    }

    $register = IndicadorUniversidad::create([
        'idUniversidad' => $request->idUniversidad,
        'idIndicador' => $request->idIndicador,
        'valor' => $request->valor
    ]);
    break;

case 'facultad':
    $fac = Facultad::find($request->idFacultad);
    if (!$fac) {
        return response()->json([
            'res' => false,
            'message' => 'No es válido el id de la facultad',
            'status' => 400
        ], 400);
    }

    $register = IndicadorFacultad::create([
        'idFacultad' => $request->idFacultad,
        'idIndicador' => $request->idIndicador,
        'valor' => $request->valor
    ]);
    break;

case 'progformacion':
    $prog = ProgFormacion::find($request->idProgFormacion);
    if (!$prog) {
        return response()->json([
            'res' => false,
            'message' => 'No es válido el id del programa de formación',
            'status' => 400
        ], 400);
    }

    $register = IndicadorProgForm::create([
        'idProgFormacion' => $request->idProgFormacion,
        'idIndicador' => $request->idIndicador,
        'valor' => $request->valor
    ]);
    break;


            default:
                return response()->json([
                    'res' => false,
                    'message' => 'Tipo de asociación no válido',
                    'status' => 400
                ], 400);
        }

        if (!$register) {
            return response()->json([
                'res' => false,
                'message' => 'No se pudo crear el registro del indicador',
                'status' => 400
            ], 400);
        }

        return response()->json([
            'res' => true,
            'message' => 'Se creo correctamente el registro',
            'status' => 200
        ], 200);
    }

    public function show() {
        // Implementación pendiente según necesidades
    }

    private function update($register, Request $request) {
        if (!$register) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontro el registro del indicador',
                'status' => 400
            ], 400);
        }

        // Determinar reglas según el tipo de registro
        $rules = ['valor' => 'required'];

        if ($register instanceof IndicadorAgno || $register instanceof IndicadorAsignatura) {
            $rules['idAñoAcademico'] = 'required';
        }

        if ($register instanceof IndicadorAgno || $register instanceof IndicadorAsignatura || $register instanceof IndicadorDepartamento) {
            $rules['idCurso'] = 'required';
        }

        $val = Validator::make($request->all(), $rules);
        if ($val->fails()) {
            return response()->json([
                'res' => false,
                'message' => $val->errors(),
                'status' => 400
            ], 400);
        }

        // Actualizar solo campos relevantes
        $data = ['valor' => $request->valor];
        if ($request->has('idCurso')) {
            $data['idCurso'] = $request->idCurso;
        }

        // Para registros que requieren año académico
        if (($register instanceof IndicadorAgno || $register instanceof IndicadorAsignatura) && $request->has('idAñoAcademico')) {
            $data['idAñoAcademico'] = $request->idAñoAcademico;
        }

        $register->update($data);

        return response()->json([
            'res' => true,
            'message' => 'Se actualizo el indicador correctamente',
            'status' => 200
        ], 200);
    }

    // Métodos para año académico
    public function updateAA(Request $request, $idC, $idI, $idAA) {
        $register = IndicadorAgno::where('idCurso', $idC)
            ->where('idIndicador', $idI)
            ->where('idAñoAcademico', $idAA)
            ->first();

        return $this->update($register, $request);
    }

    public function destroyAA($idC, $idI, $idAA) {
        $register = IndicadorAgno::where('idCurso', $idC)
            ->where('idIndicador', $idI)
            ->where('idAñoAcademico', $idAA)
            ->first();

        if (!$register) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontro el registro del indicador',
                'status' => 400
            ], 400);
        }

        $register->delete();
        return response()->json([
            'res' => true,
            'message' => 'Se elimino el indicador correctamente',
            'status' => 200
        ], 200);
    }

    // Métodos para asignatura
    public function updateA(Request $request, $idC, $idI, $idAA, $idA) {
        $register = IndicadorAsignatura::where('idCurso', $idC)
            ->where('idIndicador', $idI)
            ->where('idAñoAcademico', $idAA)
            ->where('idAsignatura', $idA)
            ->first();

        return $this->update($register, $request);
    }

    public function destroyA($idC, $idI, $idAA, $idA) {
        $register = IndicadorAsignatura::where('idCurso', $idC)
            ->where('idIndicador', $idI)
            ->where('idAñoAcademico', $idAA)
            ->where('idAsignatura', $idA)
            ->first();

        if (!$register) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontro el registro del indicador',
                'status' => 400
            ], 400);
        }

        $register->delete();
        return response()->json([
            'res' => true,
            'message' => 'Se elimino el indicador correctamente',
            'status' => 200
        ], 200);
    }

    // Métodos para departamento
    public function updateD(Request $request, $idD, $idI, $idC) {
        $register = IndicadorDepartamento::where('idDepartamento', $idD)
            ->where('idIndicador', $idI)
            ->where('idCurso', $idC)
            ->first();

        return $this->update($register, $request);
    }

    public function destroyD($idD, $idI, $idC) {
        $register = IndicadorDepartamento::where('idDepartamento', $idD)
            ->where('idIndicador', $idI)
            ->where('idCurso', $idC)
            ->first();

        if (!$register) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontro el registro del indicador',
                'status' => 400
            ], 400);
        }

        $register->delete();
        return response()->json([
            'res' => true,
            'message' => 'Se elimino el indicador correctamente',
            'status' => 200
        ], 200);
    }
}
