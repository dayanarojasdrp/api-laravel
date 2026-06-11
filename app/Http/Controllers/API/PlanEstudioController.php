<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanEstudio;
use App\Models\ProgFormacion;
use App\Models\PlanEstudioProgForm;
use App\Models\PlanEstudio_Curriculo;
use App\Models\Curriculo;
use App\Models\Curriculo_Disciplina;
use App\Models\Disciplina;
use App\Models\Disciplina_Asignatura;
use App\Models\Asignatura;
use App\Models\Asignatura_Agno;
use App\Models\Modificacion;
use App\Models\PlanNotification;
use App\Models\UserAppAccess;
use App\Models\Version;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlanEstudioController extends Controller
{
    public function index()
    {
        $planes = PlanEstudio::with([
            'programaFormacion',
            'curso',
            'modalidad',
            'calificacion',
            'modificacion',
            'planOrigen',
        ])->get();

        return response()->json([
            'res' => true,
            'data' => $planes
        ], 200);
    }

    public function store(Request $request)
    {
        $request->merge($this->normalizePlanEstudioInput($request));

        $validator = Validator::make($request->all(), [
            'nombre' => ['nullable', 'string', 'max:255'],
            'id_prog_form' => ['required', 'integer', 'exists:programa_de_formacion,id'],
            'id_curso' => ['required', 'integer', 'exists:curso,id'],
            'id_modalidad' => ['required', 'integer', 'exists:modalidad_carrera,id'],
            'id_calificacion' => ['required', 'integer', 'exists:calificacion,id'],
            'id_curriculo' => ['sometimes', 'array'],
            'id_curriculo.*' => ['integer', 'exists:curriculo,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if ($this->calificacionAsignadaAOtroPrograma($request->id_calificacion, $request->id_prog_form)) {
            return response()->json([
                'res' => false,
                'message' => 'La calificación ya está asociada a otro programa de formación.'
            ], 400);
        }

        $programa = ProgFormacion::find($request->id_prog_form);
        $nombrePlan = $request->filled('nombre')
            ? $request->nombre
            : 'Plan de Estudio ' . $programa->nombre;

        $plan = DB::transaction(function () use ($request, $nombrePlan) {
            $plan = PlanEstudio::create([
                'nombre'=> $nombrePlan,
                'id_prog_form' => $request->id_prog_form,
                'id_curso' => $request->id_curso,
                'id_modalidad' => $request->id_modalidad,
                'id_calificacion' => $request->id_calificacion,
                'estado' => 'esperando_aprobacion',
                'tipo_plan' => 'original',
            ]);

            Version::create([
                'nombre' => 'Versión inicial',
                'plan_estudio_id' => $plan->id,
            ]);

            ProgFormacion::where('id', $request->id_prog_form)
                ->update(['id_calificacion' => $request->id_calificacion]);

            PlanEstudioProgForm::firstOrCreate([
                'plan_estudio_id' => $plan->id,
                'programa_de_formacion_id' => $request->id_prog_form,
            ]);

            $curriculos = array_values(array_unique($request->input('id_curriculo', [])));

            foreach ($curriculos as $idCurriculo) {
                PlanEstudio_Curriculo::firstOrCreate([
                    'id_plan_estudio' => $plan->id,
                    'id_curriculo' => $idCurriculo,
                ]);
            }

            return $plan;
        });

        return response()->json([
            'res' => true,
            'message' => 'Plan de estudio creado correctamente',
            'data' => $plan->load([
                'programaFormacion',
                'curso',
                'modalidad',
                'calificacion',
                'curriculos',
                'modificacion',
                'planOrigen',
            ])
        ], 200);
    }

    public function show(string $id)
    {
        $plan = PlanEstudio::find($id);

        if (!$plan) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el plan de estudio'
            ], 400);
        }

        return response()->json([
            'res' => true,
            'data' => $plan->load([
                'programaFormacion',
                'curso',
                'modalidad',
                'calificacion',
                'curriculos',
                'modificacion',
                'planOrigen',
            ])
        ], 200);
    }

    public function solicitudesDecano(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facultad_id' => ['required', 'integer', 'exists:facultad,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $planIds = DB::table('plan-estudio as pe')
            ->join('departamento_prog_d_form as dpf', 'pe.id_prog_form', '=', 'dpf.id_prog_form')
            ->join('facultad_departamento as fd', 'dpf.id_departamento', '=', 'fd.id_departamento')
            ->where('fd.id_facultad', $request->facultad_id)
            ->where('pe.estado', 'enviado_decano')
            ->whereIn('pe.tipo_plan', ['original', 'modificacion'])
            ->pluck('pe.id');

        $planes = PlanEstudio::with([
            'programaFormacion',
            'curso',
            'modalidad',
            'calificacion',
            'curriculos',
            'modificacion',
            'planOrigen',
        ])
            ->whereIn('id', $planIds)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'res' => true,
            'data' => $planes,
        ], 200);
    }

    public function historialSolicitudesDecano(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facultad_id' => ['required', 'integer', 'exists:facultad,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $planIds = DB::table('plan-estudio as pe')
            ->join('departamento_prog_d_form as dpf', 'pe.id_prog_form', '=', 'dpf.id_prog_form')
            ->join('facultad_departamento as fd', 'dpf.id_departamento', '=', 'fd.id_departamento')
            ->where('fd.id_facultad', $request->facultad_id)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('plan_notifications as pn')
                    ->whereColumn('pn.plan_estudio_id', 'pe.id')
                    ->whereIn('pn.type', ['plan_nuevo_enviado', 'plan_modificacion_enviada']);
            })
            ->pluck('pe.id');

        $planes = PlanEstudio::with([
            'programaFormacion',
            'curso',
            'modalidad',
            'calificacion',
            'curriculos',
            'modificacion',
            'planOrigen',
        ])
            ->whereIn('id', $planIds)
            ->orderByDesc('updated_at')
            ->get();

        return response()->json([
            'res' => true,
            'data' => $planes,
        ], 200);
    }

    public function enviarModificacion(Request $request, string $id)
    {
        $plan = PlanEstudio::with('modificacion')->find($id);

        if (!$plan || !in_array($plan->tipo_plan, ['original', 'modificacion'], true)) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la modificación del plan de estudio.',
            ], 404);
        }

        $puedeEnviarPlanNuevo = $plan->tipo_plan === 'original'
            && $plan->estado === 'esperando_aprobacion';
        $puedeEnviarModificacion = $plan->tipo_plan === 'modificacion'
            && $plan->estado === 'modificado_esperando_aprobacion';

        if (!$puedeEnviarPlanNuevo && !$puedeEnviarModificacion) {
            return response()->json([
                'res' => false,
                'message' => 'Esta modificación no está pendiente de envío.',
            ], 422);
        }

        DB::transaction(function () use ($plan, $request) {
            $plan->update(['estado' => 'enviado_decano']);

            if ($plan->modificacion) {
                $plan->modificacion->update(['estado' => 'enviada']);
            }

            $this->notificarDecanosSolicitudPlan($plan->fresh(), $request->input('username'));
        });

        return response()->json([
            'res' => true,
            'message' => 'Modificación enviada al decano correctamente.',
            'data' => $plan->fresh()->load(['modificacion']),
        ], 200);
    }

    public function aprobarModificacion(Request $request, string $id)
    {
        $plan = PlanEstudio::with(['modificacion', 'planOrigen'])->find($id);

        if (!$plan || !in_array($plan->tipo_plan, ['original', 'modificacion'], true)) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la modificación del plan de estudio.',
            ], 404);
        }

        if ($plan->estado !== 'enviado_decano') {
            return response()->json([
                'res' => false,
                'message' => 'Esta modificación no está enviada al decano.',
            ], 422);
        }

        DB::transaction(function () use ($plan, $request) {
            $snapshot = $plan->modificacion?->estructura_snapshot;
            $estructura = is_array($snapshot) ? ($snapshot['estructura'] ?? null) : null;

            if (is_array($estructura)) {
                $materializacionRequest = new Request([
                    'id_prog_form' => $plan->id_prog_form,
                    'id_curso' => $plan->id_curso,
                    'id_modalidad' => $plan->id_modalidad,
                    'id_calificacion' => $plan->id_calificacion,
                    'estructura_editada' => $estructura,
                ]);

                $estructuraMaterializada = $this->materializarEstructuraEditada(
                    $estructura,
                    (int) $plan->id_prog_form,
                    $materializacionRequest
                );

                $plan->modificacion->update([
                    'estructura_snapshot' => $estructuraMaterializada,
                ]);
            }

            PlanEstudio::where('id_prog_form', $plan->id_prog_form)
                ->where('id', '<>', $plan->id)
                ->whereIn('estado', ['vigente', 'esperando_aprobacion', 'modificado_esperando_aprobacion', 'enviado_decano'])
                ->update(['estado' => 'version_anterior']);

            ProgFormacion::where('id', $plan->id_prog_form)
                ->update(['id_calificacion' => $plan->id_calificacion]);

            $plan->update([
                'estado' => 'vigente',
                'tipo_plan' => 'vigente',
            ]);

            if ($plan->modificacion) {
                $plan->modificacion->update(['estado' => 'aprobada']);
            }

            $this->notificarJefesRespuestaSolicitud($plan->fresh(), 'aprobada', $request->input('username'));
        });

        return response()->json([
            'res' => true,
            'message' => 'Modificación aprobada correctamente.',
            'data' => $plan->fresh()->load([
                'programaFormacion',
                'curso',
                'modalidad',
                'calificacion',
                'curriculos',
                'modificacion',
                'planOrigen',
            ]),
        ], 200);
    }

    public function cancelarModificacion(Request $request, string $id)
    {
        $plan = PlanEstudio::with('modificacion')->find($id);

        if (!$plan || !in_array($plan->tipo_plan, ['original', 'modificacion'], true)) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró la modificación del plan de estudio.',
            ], 404);
        }

        if (!in_array($plan->estado, ['enviado_decano', 'modificado_esperando_aprobacion', 'esperando_aprobacion'], true)) {
            return response()->json([
                'res' => false,
                'message' => 'Esta modificación no se puede cancelar.',
            ], 422);
        }

        DB::transaction(function () use ($plan, $request) {
            $plan->update([
                'estado' => $plan->tipo_plan === 'original'
                    ? 'rechazado'
                    : 'modificacion_cancelada',
            ]);

            if ($plan->modificacion) {
                $plan->modificacion->update(['estado' => 'cancelada']);
            }

            $this->notificarJefesRespuestaSolicitud($plan->fresh(), 'cancelada', $request->input('username'));
        });

        return response()->json([
            'res' => true,
            'message' => 'Modificación cancelada correctamente.',
            'data' => $plan->fresh()->load(['modificacion']),
        ], 200);
    }

    public function modificar(Request $request, string $id)
    {
        $request->merge($this->normalizePlanEstudioInput($request));
        $planOrigen = PlanEstudio::with(['curriculos', 'programaFormacion', 'curso', 'modalidad', 'calificacion'])->find($id);

        if (!$planOrigen) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el plan de estudio original'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => ['nullable', 'string', 'max:255'],
            'id_prog_form' => ['required', 'integer', 'exists:programa_de_formacion,id'],
            'id_curso' => ['required', 'integer', 'exists:curso,id'],
            'id_modalidad' => ['required', 'integer', 'exists:modalidad_carrera,id'],
            'id_calificacion' => ['required', 'integer', 'exists:calificacion,id'],
            'id_curriculo' => ['required', 'array', 'min:1'],
            'id_curriculo.*' => ['integer', 'exists:curriculo,id'],
            'estructura_editada' => ['sometimes', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if ($this->calificacionAsignadaAOtroPrograma($request->id_calificacion, $request->id_prog_form)) {
            return response()->json([
                'res' => false,
                'message' => 'La calificación ya está asociada a otro programa de formación.'
            ], 400);
        }

        $programa = ProgFormacion::find($request->id_prog_form);
        $nombrePlan = $request->filled('nombre')
            ? $request->nombre
            : $planOrigen->nombre . ' - Modificación';

        $estructuraSnapshot = $this->estructuraSnapshotDesdeRequest($request);
        $resumenCambios = $this->compararSnapshots(
            $this->snapshotPlan($planOrigen),
            $estructuraSnapshot ?: $this->snapshotPlan($this->planPreviewDesdeRequest($request))
        );

        if (!$this->resumenTieneCambios($resumenCambios)) {
            return response()->json([
                'res' => true,
                'no_changes' => true,
                'message' => 'No se crearon modificaciones porque el plan no tiene cambios.',
                'resumen_cambios' => $resumenCambios,
            ], 200);
        }

        $resultado = DB::transaction(function () use ($request, $planOrigen, $nombrePlan, $programa, $resumenCambios, $estructuraSnapshot) {
            $planModificado = PlanEstudio::create([
                'nombre' => $nombrePlan,
                'id_prog_form' => $request->id_prog_form,
                'id_curso' => $request->id_curso,
                'id_modalidad' => $request->id_modalidad,
                'id_calificacion' => $request->id_calificacion,
                'estado' => 'modificado_esperando_aprobacion',
                'tipo_plan' => 'modificacion',
                'plan_origen_id' => $planOrigen->id,
            ]);

            PlanEstudioProgForm::firstOrCreate([
                'plan_estudio_id' => $planModificado->id,
                'programa_de_formacion_id' => $request->id_prog_form,
            ]);

            $curriculos = array_values(array_unique($request->input('id_curriculo', [])));

            foreach ($curriculos as $idCurriculo) {
                PlanEstudio_Curriculo::firstOrCreate([
                    'id_plan_estudio' => $planModificado->id,
                    'id_curriculo' => $idCurriculo,
                ]);
            }

            $version = Version::create([
                'nombre' => 'Modificación de ' . $planOrigen->nombre,
                'plan_estudio_id' => $planModificado->id,
            ]);

            $modificacion = Modificacion::create([
                'nombre' => 'Modificación propuesta para ' . ($programa->nombre ?? $planOrigen->nombre),
                'version_id' => $version->id,
                'plan_origen_id' => $planOrigen->id,
                'plan_modificado_id' => $planModificado->id,
                'estado' => 'esperando_aprobacion',
                'resumen_cambios' => $resumenCambios,
                'estructura_snapshot' => $estructuraSnapshot,
            ]);

            return [
                'plan' => $planModificado,
                'modificacion' => $modificacion,
                'resumen_cambios' => $resumenCambios,
            ];
        });

        return response()->json([
            'res' => true,
            'message' => 'Modificación de plan de estudio creada correctamente',
            'data' => $resultado['plan']->fresh()->load([
                'programaFormacion',
                'curso',
                'modalidad',
                'calificacion',
                'curriculos',
                'modificacion',
                'planOrigen',
            ]),
            'modificacion' => $resultado['modificacion'],
            'resumen_cambios' => $resultado['resumen_cambios'],
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $request->merge($this->normalizePlanEstudioInput($request));
        $plan = PlanEstudio::find($id);

        if (!$plan) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el plan de estudio'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => ['sometimes', 'nullable', 'string', 'max:255'],
            'id_prog_form' => ['sometimes', 'integer', 'exists:programa_de_formacion,id'],
            'id_curso' => ['sometimes', 'integer', 'exists:curso,id'],
            'id_modalidad' => ['sometimes', 'integer', 'exists:modalidad_carrera,id'],
            'id_calificacion' => ['sometimes', 'integer', 'exists:calificacion,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $programaId = $request->id_prog_form ?? $plan->id_prog_form;
        $calificacionId = $request->id_calificacion ?? $plan->id_calificacion;

        if ($programaId && $calificacionId && $this->calificacionAsignadaAOtroPrograma($calificacionId, $programaId)) {
            return response()->json([
                'res' => false,
                'message' => 'La calificación ya está asociada a otro programa de formación.'
            ], 400);
        }

        $data = [];
        foreach (['nombre', 'id_prog_form', 'id_curso', 'id_modalidad', 'id_calificacion'] as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->$field;
            }
        }

        DB::transaction(function () use ($plan, $data, $programaId, $calificacionId) {
            $plan->update($data);
    
            if ($programaId && $calificacionId) {
                ProgFormacion::where('id', $programaId)->update(['id_calificacion' => $calificacionId]);
            }

            if ($programaId) {
                PlanEstudioProgForm::firstOrCreate([
                    'plan_estudio_id' => $plan->id,
                    'programa_de_formacion_id' => $programaId,
                ]);
            }
        });

        return response()->json([
            'res' => true,
            'message' => 'Plan de estudio actualizado',
            'data' => $plan->fresh()->load([
                'programaFormacion',
                'curso',
                'modalidad',
                'calificacion',
                'curriculos',
                'modificacion',
                'planOrigen',
            ])
        ], 200);
    }

    private function compararPlanes(PlanEstudio $planOrigen, PlanEstudio $planModificado): array
    {
        return $this->compararSnapshots(
            $this->snapshotPlan($planOrigen),
            $this->snapshotPlan($planModificado)
        );
    }

    private function compararSnapshots(array $origen, array $nuevo): array
    {
        return [
            'datos_base' => $this->compararDatosBase($origen['datos_base'], $nuevo['datos_base']),
            'curriculos_agregados' => $this->labelsPorLlavesNuevas($origen['curriculos_detalle'], $nuevo['curriculos_detalle']),
            'curriculos_eliminados' => $this->labelsPorLlavesNuevas($nuevo['curriculos_detalle'], $origen['curriculos_detalle']),
            'disciplinas_agregadas' => $this->labelsPorLlavesNuevas($origen['disciplinas_detalle'], $nuevo['disciplinas_detalle']),
            'disciplinas_eliminadas' => $this->labelsPorLlavesNuevas($nuevo['disciplinas_detalle'], $origen['disciplinas_detalle']),
            'disciplinas_modificadas' => $this->compararNombres('disciplina', $origen['disciplinas_detalle'], $nuevo['disciplinas_detalle']),
            'asignaturas_agregadas' => $this->labelsPorLlavesNuevas($origen['asignaturas_detalle'], $nuevo['asignaturas_detalle']),
            'asignaturas_eliminadas' => $this->labelsPorLlavesNuevas($nuevo['asignaturas_detalle'], $origen['asignaturas_detalle']),
            'asignaturas_modificadas' => $this->compararAsignaturas($origen['asignaturas_detalle'], $nuevo['asignaturas_detalle']),
        ];
    }

    private function planPreviewDesdeRequest(Request $request): PlanEstudio
    {
        $plan = new PlanEstudio([
            'nombre' => $request->input('nombre'),
            'id_prog_form' => $request->id_prog_form,
            'id_curso' => $request->id_curso,
            'id_modalidad' => $request->id_modalidad,
            'id_calificacion' => $request->id_calificacion,
        ]);

        $curriculos = Curriculo::whereIn(
            'id',
            array_values(array_unique($request->input('id_curriculo', [])))
        )->get();

        $plan->setRelation('curriculos', $curriculos);

        return $plan;
    }

    private function resumenTieneCambios(array $resumenCambios): bool
    {
        foreach ($resumenCambios as $items) {
            if (is_array($items) && count($items) > 0) {
                return true;
            }
        }

        return false;
    }

    private function estructuraSnapshotDesdeRequest(Request $request): ?array
    {
        if (!$request->has('estructura_editada')) {
            return null;
        }

        $curriculos = collect($request->input('estructura_editada', []));
        $curriculosDetalle = [];
        $disciplinasDetalle = [];
        $asignaturasDetalle = [];

        foreach ($curriculos as $curriculo) {
            $curriculoId = (string) ($curriculo['id'] ?? '');
            if ($curriculoId === '') {
                continue;
            }

            $curriculoNombre = trim((string) ($curriculo['nombre'] ?? 'Currículo'));
            $curriculosDetalle[$curriculoId] = [
                'nombre' => $curriculoNombre,
                'label' => $curriculoNombre,
            ];

            foreach (($curriculo['disciplinas'] ?? []) as $disciplina) {
                $disciplinaId = (string) ($disciplina['id'] ?? '');
                if ($disciplinaId === '') {
                    continue;
                }

                $disciplinaNombre = trim((string) ($disciplina['nombre'] ?? 'Disciplina'));
                $disciplinaKey = $curriculoId . ':' . $disciplinaId;
                $disciplinasDetalle[$disciplinaKey] = [
                    'nombre' => $disciplinaNombre,
                    'label' => $curriculoNombre . ' / ' . $disciplinaNombre,
                ];

                foreach (($disciplina['asignaturas'] ?? []) as $asignatura) {
                    $asignaturaId = (string) ($asignatura['id_asignatura_existente'] ?? $asignatura['id'] ?? '');
                    if ($asignaturaId === '') {
                        continue;
                    }

                    $asignaturaNombre = trim((string) ($asignatura['nombre'] ?? 'Asignatura'));
                    $asignaturaKey = $disciplinaKey . ':' . $asignaturaId;
                    $horasClase = (int) ($asignatura['horas_clase'] ?? $asignatura['fondo_tiempo'] ?? 0);
                    $horasPractica = (int) ($asignatura['horas_practica_laboral'] ?? 0);

                    $asignaturasDetalle[$asignaturaKey] = [
                        'nombre' => $asignaturaNombre,
                        'label' => $curriculoNombre . ' / ' . $disciplinaNombre . ' / ' . $asignaturaNombre,
                        'fondo_tiempo' => $horasClase + $horasPractica,
                        'horas_clase' => $horasClase,
                        'horas_practica_laboral' => $horasPractica,
                        'anios' => $this->nombresAniosDesdeAsignaturaPayload($asignatura),
                    ];
                }
            }
        }

        return [
            'datos_base' => [
                'Programa' => ProgFormacion::find($request->id_prog_form)?->nombre,
                'Modalidad' => DB::table('modalidad_carrera')->where('id', $request->id_modalidad)->value('nombre'),
                'Calificación' => DB::table('calificacion')->where('id', $request->id_calificacion)->value('nombre'),
                'Curso' => $request->filled('id_curso')
                    ? DB::table('curso')->where('id', $request->id_curso)->value('curso')
                    : null,
            ],
            'curriculos_detalle' => $curriculosDetalle,
            'disciplinas_detalle' => $disciplinasDetalle,
            'asignaturas_detalle' => $asignaturasDetalle,
            'estructura' => $curriculos->values()->all(),
        ];
    }

    private function materializarEstructuraEditada(array $estructura, int $programaId, Request $request): array
    {
        $estructuraMaterializada = [];

        foreach ($estructura as $curriculoData) {
            $curriculoId = (int) ($curriculoData['id'] ?? 0);
            $curriculo = Curriculo::find($curriculoId);

            if (!$curriculo) {
                continue;
            }

            $disciplinasMaterializadas = [];

            foreach (($curriculoData['disciplinas'] ?? []) as $disciplinaData) {
                $disciplina = $this->resolverDisciplina($disciplinaData);

                Curriculo_Disciplina::firstOrCreate([
                    'id_curriculo' => $curriculo->id,
                    'id_disciplina' => $disciplina->id,
                ]);

                $asignaturasMaterializadas = [];

                foreach (($disciplinaData['asignaturas'] ?? []) as $asignaturaData) {
                    $asignatura = $this->resolverAsignatura($asignaturaData);

                    Disciplina_Asignatura::firstOrCreate([
                        'id_disciplina' => $disciplina->id,
                        'id_asignatura' => $asignatura->id,
                    ]);

                    $anioIds = $this->resolverAniosAsignatura($asignaturaData, $programaId);

                    foreach ($anioIds as $anioId) {
                        Asignatura_Agno::firstOrCreate([
                            'id_asignatura' => $asignatura->id,
                            'id_a_academico' => $anioId,
                        ]);
                    }

                    $anios = DB::table('a_academico')
                        ->whereIn('id', $anioIds)
                        ->orderBy('identificador')
                        ->get(['id', 'identificador', 'id_prog_form'])
                        ->map(fn ($anio) => [
                            'id' => $anio->id,
                            'identificador' => $anio->identificador,
                            'id_prog_form' => $anio->id_prog_form,
                        ])
                        ->values()
                        ->all();

                    $asignaturasMaterializadas[] = [
                        'id' => $asignatura->id,
                        'nombre' => $asignaturaData['nombre'] ?? $asignatura->nombre,
                        'fondo_tiempo' => $this->totalHorasAsignaturaPayload($asignaturaData),
                        'horas_clase' => (int) ($asignaturaData['horas_clase'] ?? $asignatura->horas_clase ?? 0),
                        'horas_practica_laboral' => (int) ($asignaturaData['horas_practica_laboral'] ?? $asignatura->horas_practica_laboral ?? 0),
                        'anios' => $anios,
                    ];
                }

                $disciplina->update([
                    'fondo_tiempo' => collect($asignaturasMaterializadas)
                        ->sum(fn ($asignatura) => (int) ($asignatura['fondo_tiempo'] ?? 0)),
                ]);

                $disciplinasMaterializadas[] = [
                    'id' => $disciplina->id,
                    'nombre' => $disciplina->fresh()->nombre,
                    'asignaturas' => $asignaturasMaterializadas,
                ];
            }

            $estructuraMaterializada[] = [
                'id' => $curriculo->id,
                'nombre' => $curriculo->nombre,
                'disciplinas' => $disciplinasMaterializadas,
            ];
        }

        return $this->snapshotDesdeEstructuraMaterializada($estructuraMaterializada, $request);
    }

    private function resolverDisciplina(array $disciplinaData): Disciplina
    {
        $id = $disciplinaData['id'] ?? null;

        if (is_numeric($id) && !($disciplinaData['is_new'] ?? false)) {
            $disciplina = Disciplina::findOrFail((int) $id);
            $disciplina->update([
                'nombre' => trim((string) ($disciplinaData['nombre'] ?? $disciplina->nombre)),
            ]);

            return $disciplina;
        }

        return Disciplina::firstOrCreate(
            ['nombre' => trim((string) ($disciplinaData['nombre'] ?? 'Nueva disciplina'))],
            ['fondo_tiempo' => 0]
        );
    }

    private function resolverAsignatura(array $asignaturaData): Asignatura
    {
        $idExistente = $asignaturaData['id_asignatura_existente'] ?? null;
        $id = $asignaturaData['id'] ?? null;

        if (is_numeric($idExistente)) {
            return $this->actualizarAsignaturaDesdePayload(
                Asignatura::findOrFail((int) $idExistente),
                $asignaturaData
            );
        }

        if (is_numeric($id) && !($asignaturaData['is_new'] ?? false)) {
            return $this->actualizarAsignaturaDesdePayload(
                Asignatura::findOrFail((int) $id),
                $asignaturaData
            );
        }

        $horasClase = (int) ($asignaturaData['horas_clase'] ?? 0);
        $horasPractica = (int) ($asignaturaData['horas_practica_laboral'] ?? 0);

        return Asignatura::create([
            'nombre' => trim((string) ($asignaturaData['nombre'] ?? 'Nueva asignatura')),
            'fondo_tiempo' => $horasClase + $horasPractica,
            'horas_clase' => $horasClase,
            'horas_practica_laboral' => $horasPractica,
        ]);
    }

    private function actualizarAsignaturaDesdePayload(Asignatura $asignatura, array $asignaturaData): Asignatura
    {
        $horasClase = (int) ($asignaturaData['horas_clase'] ?? $asignatura->horas_clase ?? 0);
        $horasPractica = (int) ($asignaturaData['horas_practica_laboral'] ?? $asignatura->horas_practica_laboral ?? 0);

        $asignatura->update([
            'nombre' => trim((string) ($asignaturaData['nombre'] ?? $asignatura->nombre)),
            'fondo_tiempo' => $horasClase + $horasPractica,
            'horas_clase' => $horasClase,
            'horas_practica_laboral' => $horasPractica,
        ]);

        return $asignatura;
    }

    private function resolverAniosAsignatura(array $asignaturaData, int $programaId): array
    {
        $ids = collect($asignaturaData['id_a_academico'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        if ($ids->isEmpty() && isset($asignaturaData['anios'])) {
            $ids = collect($asignaturaData['anios'])
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->values();
        }

        return DB::table('a_academico')
            ->where('id_prog_form', $programaId)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    private function totalHorasAsignaturaPayload(array $asignaturaData): int
    {
        return (int) ($asignaturaData['horas_clase'] ?? 0)
            + (int) ($asignaturaData['horas_practica_laboral'] ?? 0);
    }

    private function nombresAniosDesdeAsignaturaPayload(array $asignatura): array
    {
        if (isset($asignatura['anios'])) {
            return collect($asignatura['anios'])
                ->pluck('identificador')
                ->filter()
                ->values()
                ->all();
        }

        return DB::table('a_academico')
            ->whereIn('id', $asignatura['id_a_academico'] ?? [])
            ->orderBy('identificador')
            ->pluck('identificador')
            ->values()
            ->all();
    }

    private function snapshotDesdeEstructuraMaterializada(array $estructura, Request $request): array
    {
        $snapshotRequest = new Request([
            'id_prog_form' => $request->id_prog_form,
            'id_curso' => $request->id_curso,
            'id_modalidad' => $request->id_modalidad,
            'id_calificacion' => $request->id_calificacion,
            'estructura_editada' => $estructura,
        ]);

        return $this->estructuraSnapshotDesdeRequest($snapshotRequest);
    }

    private function snapshotPlan(PlanEstudio $plan): array
    {
        $plan->loadMissing(['curriculos', 'programaFormacion', 'curso', 'modalidad', 'calificacion']);
        $curriculoIds = $plan->curriculos->pluck('id')->map(fn ($id) => (int) $id)->all();
        $curriculos = Curriculo::with([
            'disciplinas' => fn ($query) => $query->orderBy('nombre'),
            'disciplinas.asignaturas' => fn ($query) => $query->orderBy('nombre'),
            'disciplinas.asignaturas.aniosAcademicos',
        ])->whereIn('id', $curriculoIds)->orderBy('nombre')->get();

        $curriculosDetalle = [];
        $disciplinasDetalle = [];
        $asignaturasDetalle = [];

        foreach ($curriculos as $curriculo) {
            $curriculosDetalle[(string) $curriculo->id] = [
                'nombre' => $curriculo->nombre,
                'label' => $curriculo->nombre,
            ];

            foreach ($curriculo->disciplinas as $disciplina) {
                $asignaturasFiltradas = $disciplina->asignaturas->filter(function ($asignatura) use ($plan) {
                    return $asignatura->aniosAcademicos
                        ->where('id_prog_form', $plan->id_prog_form)
                        ->isNotEmpty();
                });

                if ($asignaturasFiltradas->isEmpty()) {
                    continue;
                }

                $disciplinaKey = $curriculo->id . ':' . $disciplina->id;
                $disciplinasDetalle[$disciplinaKey] = [
                    'nombre' => $disciplina->nombre,
                    'label' => $curriculo->nombre . ' / ' . $disciplina->nombre,
                ];

                foreach ($asignaturasFiltradas as $asignatura) {
                    $label = $curriculo->nombre . ' / ' . $disciplina->nombre . ' / ' . $asignatura->nombre;
                    $asignaturaKey = $disciplinaKey . ':' . $asignatura->id;
                    $anios = $asignatura->aniosAcademicos
                        ->where('id_prog_form', $plan->id_prog_form)
                        ->pluck('identificador')
                        ->values()
                        ->all();

                    $asignaturasDetalle[$asignaturaKey] = [
                        'nombre' => $asignatura->nombre,
                        'label' => $label,
                        'fondo_tiempo' => (int) ($asignatura->fondo_tiempo ?? 0),
                        'horas_clase' => (int) ($asignatura->horas_clase ?? $asignatura->fondo_tiempo ?? 0),
                        'horas_practica_laboral' => (int) ($asignatura->horas_practica_laboral ?? 0),
                        'anios' => $anios,
                    ];
                }
            }
        }

        return [
            'datos_base' => [
                'Programa' => $plan->programaFormacion?->nombre,
                'Modalidad' => $plan->modalidad?->nombre,
                'Calificación' => $plan->calificacion?->nombre,
                'Curso' => $plan->curso?->curso,
            ],
            'curriculos_detalle' => $curriculosDetalle,
            'disciplinas_detalle' => $disciplinasDetalle,
            'asignaturas_detalle' => $asignaturasDetalle,
        ];
    }

    private function labelsPorLlavesNuevas(array $base, array $comparado): array
    {
        return collect(array_diff_key($comparado, $base))
            ->map(fn ($item) => $item['label'] ?? $item['nombre'] ?? '')
            ->filter()
            ->values()
            ->all();
    }

    private function compararNombres(string $tipo, array $origen, array $nuevo): array
    {
        $cambios = [];

        foreach ($nuevo as $key => $itemNuevo) {
            if (!isset($origen[$key])) {
                continue;
            }

            if (($origen[$key]['nombre'] ?? null) !== ($itemNuevo['nombre'] ?? null)) {
                $cambios[] = [
                    $tipo => $itemNuevo['label'] ?? $itemNuevo['nombre'],
                    'cambios' => [[
                        'campo' => 'nombre',
                        'antes' => $origen[$key]['nombre'] ?? null,
                        'despues' => $itemNuevo['nombre'] ?? null,
                    ]],
                ];
            }
        }

        return $cambios;
    }

    private function compararDatosBase(array $origen, array $nuevo): array
    {
        $cambios = [];

        foreach ($nuevo as $campo => $valorNuevo) {
            $valorAnterior = $origen[$campo] ?? null;
            if ($valorAnterior !== $valorNuevo) {
                $cambios[] = [
                    'campo' => $campo,
                    'antes' => $valorAnterior,
                    'despues' => $valorNuevo,
                ];
            }
        }

        return $cambios;
    }

    private function compararAsignaturas(array $origen, array $nuevo): array
    {
        $cambios = [];

        foreach ($nuevo as $label => $detalleNuevo) {
            if (!isset($origen[$label])) {
                continue;
            }

            $detalles = [];
            if (($origen[$label]['nombre'] ?? null) !== ($detalleNuevo['nombre'] ?? null)) {
                $detalles[] = [
                    'campo' => 'nombre',
                    'antes' => $origen[$label]['nombre'] ?? null,
                    'despues' => $detalleNuevo['nombre'] ?? null,
                ];
            }

            foreach (['fondo_tiempo', 'horas_clase', 'horas_practica_laboral'] as $campo) {
                if (($origen[$label][$campo] ?? null) !== ($detalleNuevo[$campo] ?? null)) {
                    $detalles[] = [
                        'campo' => $campo,
                        'antes' => $origen[$label][$campo] ?? null,
                        'despues' => $detalleNuevo[$campo] ?? null,
                    ];
                }
            }

            $aniosOrigen = $this->normalizarListaComparable($origen[$label]['anios'] ?? []);
            $aniosNuevo = $this->normalizarListaComparable($detalleNuevo['anios'] ?? []);

            if ($aniosOrigen !== $aniosNuevo) {
                $detalles[] = [
                    'campo' => 'años académicos',
                    'antes' => implode(', ', $aniosOrigen),
                    'despues' => implode(', ', $aniosNuevo),
                ];
            }

            if ($detalles) {
                $cambios[] = [
                    'asignatura' => $detalleNuevo['label'] ?? $detalleNuevo['nombre'] ?? $label,
                    'cambios' => $detalles,
                ];
            }
        }

        return $cambios;
    }

    private function normalizarListaComparable(array $items): array
    {
        $normalizados = collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique()
            ->values()
            ->all();

        sort($normalizados, SORT_NATURAL | SORT_FLAG_CASE);

        return $normalizados;
    }

    private function normalizePlanEstudioInput(Request $request): array
    {
        $aliases = [
            'id_prog_form' => 'programa_de_formacion_id',
            'id_curso' => 'curso_id',
            'id_modalidad' => 'modalidad_id',
            'id_calificacion' => 'calificacion_id',
        ];

        $data = [];

        foreach ($aliases as $field => $alias) {
            if ($request->filled($field)) {
                $data[$field] = $request->input($field);
            } elseif ($request->filled($alias)) {
                $data[$field] = $request->input($alias);
            }
        }

        return $data;
    }

    private function calificacionAsignadaAOtroPrograma(int $calificacionId, int $programaId): bool
    {
        return ProgFormacion::where('id_calificacion', $calificacionId)
            ->where('id', '<>', $programaId)
            ->exists();
    }

    private function contextoAcademicoPlan(PlanEstudio $plan): ?object
    {
        return DB::table('departamento_prog_d_form as dpf')
            ->join('departamento as d', 'dpf.id_departamento', '=', 'd.id')
            ->join('facultad_departamento as fd', 'd.id', '=', 'fd.id_departamento')
            ->join('facultad as f', 'fd.id_facultad', '=', 'f.id')
            ->join('programa_de_formacion as pf', 'dpf.id_prog_form', '=', 'pf.id')
            ->where('dpf.id_prog_form', $plan->id_prog_form)
            ->select(
                'd.id as departamento_id',
                'd.nombre as departamento_nombre',
                'f.id as facultad_id',
                'f.nombre as facultad_nombre',
                'pf.nombre as programa_nombre'
            )
            ->first();
    }

    private function notificarDecanosSolicitudPlan(PlanEstudio $plan, ?string $senderUsername): void
    {
        $contexto = $this->contextoAcademicoPlan($plan);

        if (!$contexto) {
            return;
        }

        $destinatarios = UserAppAccess::where('application_code', UserAppAccess::APPLICATION_GESTION_PLAN_ESTUDIO)
            ->where('role', 'decano')
            ->where('active', true)
            ->where('facultad_id', $contexto->facultad_id)
            ->pluck('username')
            ->filter()
            ->unique();

        foreach ($destinatarios as $username) {
            $esPlanNuevo = $plan->tipo_plan === 'original';
            PlanNotification::create([
                'recipient_username' => $username,
                'sender_username' => $senderUsername,
                'type' => $esPlanNuevo ? 'plan_nuevo_enviado' : 'plan_modificacion_enviada',
                'title' => $esPlanNuevo
                    ? 'Nuevo plan de estudio recibido'
                    : 'Solicitud de modificacion recibida',
                'body' => $esPlanNuevo
                    ? 'El jefe de departamento de ' . $contexto->departamento_nombre . ' creo un nuevo plan de estudio: ' . $plan->nombre . '.'
                    : 'El jefe de departamento de ' . $contexto->departamento_nombre . ' envio una solicitud de modificacion del plan ' . $plan->nombre . '.',
                'plan_estudio_id' => $plan->id,
                'data' => [
                    'departamento_id' => $contexto->departamento_id,
                    'departamento_nombre' => $contexto->departamento_nombre,
                    'facultad_id' => $contexto->facultad_id,
                    'facultad_nombre' => $contexto->facultad_nombre,
                    'programa_nombre' => $contexto->programa_nombre,
                ],
            ]);
        }
    }

    private function notificarJefesRespuestaSolicitud(PlanEstudio $plan, string $estado, ?string $senderUsername): void
    {
        $contexto = $this->contextoAcademicoPlan($plan);

        $destinatarios = PlanNotification::where('plan_estudio_id', $plan->id)
            ->whereIn('type', ['plan_nuevo_enviado', 'plan_modificacion_enviada'])
            ->whereNotNull('sender_username')
            ->pluck('sender_username')
            ->filter()
            ->unique();

        if ($destinatarios->isEmpty() && $contexto) {
            $destinatarios = UserAppAccess::where('application_code', UserAppAccess::APPLICATION_GESTION_PLAN_ESTUDIO)
                ->where('role', 'jefe_departamento')
                ->where('active', true)
                ->where('departamento_id', $contexto->departamento_id)
                ->pluck('username')
                ->filter()
                ->unique();
        }

        $aprobada = $estado === 'aprobada';
        $esPlanNuevo = $plan->tipo_plan === 'vigente' && !$plan->modificacion
            || $plan->tipo_plan === 'original';
        $titulo = $aprobada
            ? ($esPlanNuevo ? 'Plan de estudio aprobado' : 'Modificacion aprobada')
            : ($esPlanNuevo ? 'Plan de estudio rechazado' : 'Modificacion cancelada');
        $cuerpo = $aprobada
            ? 'El decano aprobo ' . ($esPlanNuevo ? 'el nuevo plan ' : 'la modificacion del plan ') . $plan->nombre . '. El plan queda vigente.'
            : 'El decano rechazo ' . ($esPlanNuevo ? 'el nuevo plan ' : 'la modificacion del plan ') . $plan->nombre . '.';

        foreach ($destinatarios as $username) {
            PlanNotification::create([
                'recipient_username' => $username,
                'sender_username' => $senderUsername,
                'type' => $aprobada
                    ? ($esPlanNuevo ? 'plan_nuevo_aprobado' : 'plan_modificacion_aprobada')
                    : ($esPlanNuevo ? 'plan_nuevo_rechazado' : 'plan_modificacion_cancelada'),
                'title' => $titulo,
                'body' => $cuerpo,
                'plan_estudio_id' => $plan->id,
                'data' => [
                    'estado' => $estado,
                    'departamento_id' => $contexto?->departamento_id,
                    'departamento_nombre' => $contexto?->departamento_nombre,
                    'facultad_id' => $contexto?->facultad_id,
                    'facultad_nombre' => $contexto?->facultad_nombre,
                    'programa_nombre' => $contexto?->programa_nombre,
                ],
            ]);
        }
    }
    
    public function destroy(string $id)
    {
        $plan = PlanEstudio::find($id);

        if (!$plan) {
            return response()->json([
                'res' => false,
                'message' => 'No se encontró el plan de estudio'
            ], 400);
        }

        DB::transaction(function () use ($plan) {
            Modificacion::where('plan_origen_id', $plan->id)
                ->update(['plan_origen_id' => null]);

            Modificacion::where('plan_modificado_id', $plan->id)
                ->update(['plan_modificado_id' => null]);

            PlanEstudio_Curriculo::where('id_plan_estudio', $plan->id)->delete();
            PlanEstudioProgForm::where('plan_estudio_id', $plan->id)->delete();

            $plan->delete();
        });

        return response()->json([
            'res' => true,
            'message' => 'Plan de estudio eliminado'
        ], 200);
    }

}
