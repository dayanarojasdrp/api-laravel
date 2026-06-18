<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class PlanEstudioExcelController extends Controller
{
    public function preview(string $id)
    {
        return response()->json([
            'res' => true,
            'data' => $this->buildData((int) $id),
        ]);
    }

    public function download(string $id)
    {
        $data = $this->buildData((int) $id);
        $path = $this->buildOfficialWorkbook($data);
        $filename = 'plan_estudio_' . $data['plan']['id'] . '.xls';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
        ])->deleteFileAfterSend(true);
    }

    private function buildData(int $id): array
    {
        $plan = PlanEstudio::with([
            'programaFormacion',
            'curso',
            'modalidad',
            'calificacion',
            'modificacion',
            'curriculos.disciplinas.asignaturas.aniosAcademicos',
        ])->findOrFail($id);

        $anios = DB::table('a_academico')
            ->where('id_prog_form', $plan->id_prog_form)
            ->orderBy('identificador')
            ->get(['id', 'identificador'])
            ->map(fn ($anio) => [
                'id' => (int) $anio->id,
                'identificador' => (string) $anio->identificador,
            ])
            ->values();

        $snapshot = $plan->modificacion?->estructura_snapshot;
        $planSnapshot = $plan->estructura_snapshot;
        $snapshotStructure = is_array($snapshot) ? ($snapshot['estructura'] ?? null) : null;
        $snapshotStructure ??= is_array($planSnapshot) ? ($planSnapshot['estructura'] ?? null) : null;
        $snapshotStructure ??= is_array($planSnapshot) ? $this->structureFromSnapshotDetails($planSnapshot) : null;

        $rows = is_array($snapshotStructure)
            ? $this->rowsFromSnapshotStructure($snapshotStructure, $anios)
            : $this->rowsFromCurrentStructure($plan, $anios);

        $sections = $this->buildSections($rows);

        return [
            'plan' => [
                'id' => $plan->id,
                'nombre' => $plan->nombre,
                'programa' => $plan->programaFormacion?->nombre,
                'curso' => $plan->curso?->nombre ?? $plan->curso?->curso,
                'modalidad' => $plan->modalidad?->nombre,
                'calificacion' => $plan->calificacion?->nombre,
            ],
            'anios' => $anios,
            'rows' => $rows->values(),
            'sections' => $sections,
            'totales' => [
                'fondo_tiempo' => $rows->where('type', 'asignatura')->sum('fondo_tiempo'),
                'horas_clase' => $rows->where('type', 'asignatura')->sum('horas_clase'),
                'horas_practica_laboral' => $rows->where('type', 'asignatura')->sum('horas_practica_laboral'),
                'examenes_finales' => $rows->where('type', 'asignatura')->sum(fn ($row) => $row['tiene_examen_final'] ? 1 : 0),
                'trabajos_curso' => $rows->where('type', 'asignatura')->sum(fn ($row) => $row['tiene_trabajo_curso'] ? 1 : 0),
                'anios' => $this->sumarAnios($rows->where('type', 'asignatura'), $anios),
            ],
        ];
    }

    private function rowsFromCurrentStructure(PlanEstudio $plan, Collection $anios): Collection
    {
        $rows = collect();

        foreach ($plan->curriculos as $curriculo) {
            $rows->push([
                'type' => 'curriculo',
                'nombre' => mb_strtoupper((string) $curriculo->nombre),
                'fondo_tiempo' => 0,
                'horas_clase' => 0,
                'horas_practica_laboral' => 0,
                'tiene_examen_final' => false,
                'tiene_trabajo_curso' => false,
                'anios' => [],
            ]);

            foreach ($curriculo->disciplinas->sortBy('nombre') as $disciplina) {
                $asignaturas = $disciplina->asignaturas
                    ->filter(fn ($asignatura) => $this->asignaturaPerteneceAlPrograma($asignatura, (int) $plan->id_prog_form))
                    ->sortBy('nombre')
                    ->values();

                if ($asignaturas->isEmpty()) {
                    continue;
                }

                $disciplinaRowIndex = $rows->count();
                $rows->push([
                    'type' => 'disciplina',
                    'nombre' => $disciplina->nombre,
                    'fondo_tiempo' => 0,
                    'horas_clase' => 0,
                    'horas_practica_laboral' => 0,
                    'tiene_examen_final' => false,
                    'tiene_trabajo_curso' => false,
                    'anios' => [],
                ]);

                foreach ($asignaturas as $asignatura) {
                    $row = [
                        'type' => 'asignatura',
                        'nombre' => $asignatura->nombre,
                        'fondo_tiempo' => (int) ($asignatura->fondo_tiempo ?? 0),
                        'horas_clase' => (int) ($asignatura->horas_clase ?? $asignatura->fondo_tiempo ?? 0),
                        'horas_practica_laboral' => (int) ($asignatura->horas_practica_laboral ?? 0),
                        'tiene_examen_final' => (bool) $asignatura->tiene_examen_final,
                        'tiene_trabajo_curso' => (bool) $asignatura->tiene_trabajo_curso,
                        'anios' => $this->horasPorAnio($asignatura, $anios, (int) $plan->id_prog_form),
                    ];

                    $rows->push($row);
                }

                $asignaturasRows = $rows
                    ->slice($disciplinaRowIndex + 1)
                    ->filter(fn ($row) => $row['type'] === 'asignatura');

                $rows[$disciplinaRowIndex] = [
                    ...$rows[$disciplinaRowIndex],
                    'fondo_tiempo' => $asignaturasRows->sum('fondo_tiempo'),
                    'horas_clase' => $asignaturasRows->sum('horas_clase'),
                    'horas_practica_laboral' => $asignaturasRows->sum('horas_practica_laboral'),
                    'tiene_examen_final' => (bool) $asignaturasRows->sum(fn ($row) => $row['tiene_examen_final'] ? 1 : 0),
                    'tiene_trabajo_curso' => (bool) $asignaturasRows->sum(fn ($row) => $row['tiene_trabajo_curso'] ? 1 : 0),
                    'anios' => $this->sumarAnios($asignaturasRows, $anios),
                ];
            }
        }

        return $rows;
    }

    private function rowsFromSnapshotStructure(array $structure, Collection $anios): Collection
    {
        $rows = collect();

        foreach ($structure as $curriculo) {
            $rows->push([
                'type' => 'curriculo',
                'nombre' => mb_strtoupper((string) ($curriculo['nombre'] ?? '')),
                'fondo_tiempo' => 0,
                'horas_clase' => 0,
                'horas_practica_laboral' => 0,
                'tiene_examen_final' => false,
                'tiene_trabajo_curso' => false,
                'anios' => [],
            ]);

            foreach ($this->sortByName($curriculo['disciplinas'] ?? []) as $disciplina) {
                $asignaturas = collect($this->sortByName($disciplina['asignaturas'] ?? []))->values();

                if ($asignaturas->isEmpty()) {
                    continue;
                }

                $disciplinaRowIndex = $rows->count();
                $rows->push([
                    'type' => 'disciplina',
                    'nombre' => $disciplina['nombre'] ?? 'Disciplina',
                    'fondo_tiempo' => 0,
                    'horas_clase' => 0,
                    'horas_practica_laboral' => 0,
                    'tiene_examen_final' => false,
                    'tiene_trabajo_curso' => false,
                    'anios' => [],
                ]);

                foreach ($asignaturas as $asignatura) {
                    $horasClase = (int) ($asignatura['horas_clase'] ?? $asignatura['fondo_tiempo'] ?? 0);
                    $horasPractica = (int) ($asignatura['horas_practica_laboral'] ?? 0);
                    $totalHoras = (int) ($asignatura['fondo_tiempo'] ?? ($horasClase + $horasPractica));

                    $rows->push([
                        'type' => 'asignatura',
                        'nombre' => $asignatura['nombre'] ?? 'Asignatura',
                        'fondo_tiempo' => $totalHoras,
                        'horas_clase' => $horasClase,
                        'horas_practica_laboral' => $horasPractica,
                        'tiene_examen_final' => (bool) ($asignatura['tiene_examen_final'] ?? false),
                        'tiene_trabajo_curso' => (bool) ($asignatura['tiene_trabajo_curso'] ?? false),
                        'anios' => $this->horasPorAnioDesdeSnapshot($asignatura, $anios, $totalHoras),
                    ]);
                }

                $asignaturasRows = $rows
                    ->slice($disciplinaRowIndex + 1)
                    ->filter(fn ($row) => $row['type'] === 'asignatura');

                $rows[$disciplinaRowIndex] = [
                    ...$rows[$disciplinaRowIndex],
                    'fondo_tiempo' => $asignaturasRows->sum('fondo_tiempo'),
                    'horas_clase' => $asignaturasRows->sum('horas_clase'),
                    'horas_practica_laboral' => $asignaturasRows->sum('horas_practica_laboral'),
                    'tiene_examen_final' => (bool) $asignaturasRows->sum(fn ($row) => $row['tiene_examen_final'] ? 1 : 0),
                    'tiene_trabajo_curso' => (bool) $asignaturasRows->sum(fn ($row) => $row['tiene_trabajo_curso'] ? 1 : 0),
                    'anios' => $this->sumarAnios($asignaturasRows, $anios),
                ];
            }
        }

        return $rows;
    }

    private function sortByName(array $items): array
    {
        return collect($items)
            ->sortBy(fn ($item) => mb_strtoupper((string) ($item['nombre'] ?? '')))
            ->values()
            ->all();
    }

    private function structureFromSnapshotDetails(array $snapshot): ?array
    {
        if (empty($snapshot['curriculos_detalle']) || empty($snapshot['disciplinas_detalle'])) {
            return null;
        }

        $curriculos = [];

        foreach ($snapshot['curriculos_detalle'] as $curriculoId => $curriculo) {
            $curriculos[(string) $curriculoId] = [
                'id' => is_numeric($curriculoId) ? (int) $curriculoId : $curriculoId,
                'nombre' => $curriculo['nombre'] ?? $curriculo['label'] ?? 'Currículo',
                'disciplinas' => [],
            ];
        }

        foreach ($snapshot['disciplinas_detalle'] as $disciplinaKey => $disciplina) {
            [$curriculoId, $disciplinaId] = array_pad(explode(':', (string) $disciplinaKey), 2, null);

            if (!$curriculoId || !isset($curriculos[$curriculoId])) {
                continue;
            }

            $curriculos[$curriculoId]['disciplinas'][(string) $disciplinaId] = [
                'id' => is_numeric($disciplinaId) ? (int) $disciplinaId : $disciplinaId,
                'nombre' => $disciplina['nombre'] ?? $disciplina['label'] ?? 'Disciplina',
                'asignaturas' => [],
            ];
        }

        foreach (($snapshot['asignaturas_detalle'] ?? []) as $asignaturaKey => $asignatura) {
            [$curriculoId, $disciplinaId, $asignaturaId] = array_pad(explode(':', (string) $asignaturaKey), 3, null);

            if (
                !$curriculoId ||
                !$disciplinaId ||
                !isset($curriculos[$curriculoId]['disciplinas'][(string) $disciplinaId])
            ) {
                continue;
            }

            $curriculos[$curriculoId]['disciplinas'][(string) $disciplinaId]['asignaturas'][] = [
                'id' => is_numeric($asignaturaId) ? (int) $asignaturaId : $asignaturaId,
                'nombre' => $asignatura['nombre'] ?? 'Asignatura',
                'fondo_tiempo' => (int) ($asignatura['fondo_tiempo'] ?? 0),
                'horas_clase' => (int) ($asignatura['horas_clase'] ?? $asignatura['fondo_tiempo'] ?? 0),
                'horas_practica_laboral' => (int) ($asignatura['horas_practica_laboral'] ?? 0),
                'tiene_examen_final' => (bool) ($asignatura['tiene_examen_final'] ?? false),
                'tiene_trabajo_curso' => (bool) ($asignatura['tiene_trabajo_curso'] ?? false),
                'anios' => collect($asignatura['anios'] ?? [])
                    ->map(fn ($anio) => is_array($anio) ? $anio : ['identificador' => (string) $anio])
                    ->values()
                    ->all(),
            ];
        }

        return collect($curriculos)
            ->map(function ($curriculo) {
                $curriculo['disciplinas'] = collect($curriculo['disciplinas'])
                    ->map(function ($disciplina) {
                        $disciplina['asignaturas'] = array_values($disciplina['asignaturas']);

                        return $disciplina;
                    })
                    ->values()
                    ->all();

                return $curriculo;
            })
            ->values()
            ->all();
    }

    private function buildSections(Collection $rows): array
    {
        $sections = [
            'base' => collect(),
            'propio' => collect(),
            'optativo' => collect(),
        ];
        $current = null;

        foreach ($rows as $row) {
            if ($row['type'] === 'curriculo') {
                $name = mb_strtoupper($row['nombre']);
                $current = match (true) {
                    str_contains($name, 'BASE') => 'base',
                    str_contains($name, 'PROPIO') => 'propio',
                    str_contains($name, 'OPTATIVO') || str_contains($name, 'ELECTIVO') => 'optativo',
                    default => null,
                };

                continue;
            }

            if ($current) {
                $sections[$current]->push($row);
            }
        }

        return $sections;
    }

    private function asignaturaPerteneceAlPrograma($asignatura, int $programaId): bool
    {
        return $asignatura->aniosAcademicos
            ->contains(fn ($anio) => (int) $anio->id_prog_form === $programaId);
    }

    private function horasPorAnio($asignatura, Collection $anios, int $programaId): array
    {
        $ids = $asignatura->aniosAcademicos
            ->filter(fn ($anio) => (int) $anio->id_prog_form === $programaId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();

        $horas = (int) ($asignatura->fondo_tiempo ?? 0);
        $cantidad = max($ids->count(), 1);
        $horasPorAnio = intdiv($horas, $cantidad);
        $resto = $horas % $cantidad;

        return $anios->mapWithKeys(function ($anio) use ($ids, $horasPorAnio, &$resto) {
            if (! $ids->contains((int) $anio['id'])) {
                return [$anio['identificador'] => 0];
            }

            $valor = $horasPorAnio + ($resto > 0 ? 1 : 0);
            $resto = max(0, $resto - 1);

            return [$anio['identificador'] => $valor];
        })->all();
    }

    private function horasPorAnioDesdeSnapshot(array $asignatura, Collection $anios, int $horas): array
    {
        $selectedIds = collect($asignatura['id_a_academico'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $selectedNames = collect($asignatura['anios'] ?? [])
            ->map(function ($anio) {
                if (is_array($anio)) {
                    return (string) ($anio['identificador'] ?? '');
                }

                return (string) $anio;
            })
            ->filter()
            ->values();

        $selectedCount = max($selectedIds->count() + ($selectedIds->isEmpty() ? $selectedNames->count() : 0), 1);
        $horasPorAnio = intdiv($horas, $selectedCount);
        $resto = $horas % $selectedCount;

        return $anios->mapWithKeys(function ($anio) use ($selectedIds, $selectedNames, $horasPorAnio, &$resto) {
            $matchesId = $selectedIds->contains((int) $anio['id']);
            $matchesName = $selectedIds->isEmpty() && $selectedNames->contains((string) $anio['identificador']);

            if (!$matchesId && !$matchesName) {
                return [$anio['identificador'] => 0];
            }

            $valor = $horasPorAnio + ($resto > 0 ? 1 : 0);
            $resto = max(0, $resto - 1);

            return [$anio['identificador'] => $valor];
        })->all();
    }

    private function sumarAnios(Collection $rows, Collection $anios): array
    {
        return $anios->mapWithKeys(function ($anio) use ($rows) {
            $key = $anio['identificador'];

            return [$key => $rows->sum(fn ($row) => (int) ($row['anios'][$key] ?? 0))];
        })->all();
    }

    private function buildOfficialWorkbook(array $data): string
    {
        $template = storage_path('app/templates/anexo_3_plan_proceso_docente.xls');
        $spreadsheet = IOFactory::load($template);
        $sheet = $spreadsheet->getSheet(0);
        $anios = collect($data['anios'])->take(4)->values();

        $this->fillHeader($sheet, $data);
        $this->clearTemplateData($sheet);
        $baseTotals = $this->fillBaseSection($sheet, collect($data['sections']['base']), $anios);
        $propioTotals = $this->fillSimpleSection($sheet, collect($data['sections']['propio']), $anios, 88, 89, 97, 'CURRÍCULO PROPIO', 'P');
        $optativoTotals = $this->fillSimpleSection($sheet, collect($data['sections']['optativo']), $anios, 101, 102, 108, 'CURRÍCULO OPTATIVO/ELECTIVO', 'O');

        $this->writeTotals($sheet, 85, 'CURRÍCULO BASE', $baseTotals, $anios);
        $this->writeTotals($sheet, 98, 'CURRÍCULO PROPIO', $propioTotals, $anios);
        $this->writeTotals($sheet, 109, 'CURRÍCULO OPTATIVO/ELECTIVO', $optativoTotals, $anios);
        $sheet->setCellValue('L75', 'CONTINÚA');
        $sheet->setCellValue('B112', 'T O T A L E S');
        $this->writeTotals($sheet, 113, 'CURRÍCULO', $this->mergeTotals([$baseTotals, $propioTotals, $optativoTotals], $anios), $anios);

        $path = tempnam(sys_get_temp_dir(), 'plan_estudio_') . '.xls';
        (new Xls($spreadsheet))->save($path);

        return $path;
    }

    private function fillHeader($sheet, array $data): void
    {
        $sheet->setCellValue('B5', 'MODALIDAD: ' . mb_strtoupper((string) ($data['plan']['modalidad'] ?? '')));
        $sheet->setCellValue('B6', 'CARRERA: ' . mb_strtoupper((string) ($data['plan']['programa'] ?? '')));
        $sheet->setCellValue('B7', 'CALIFICACIÓN: ' . mb_strtoupper((string) ($data['plan']['calificacion'] ?? '')));
        $sheet->setCellValue('B8', 'VIGENTE A PARTIR DEL CURSO ESCOLAR ' . mb_strtoupper((string) ($data['plan']['curso'] ?? '')));
        $sheet->setCellValue('B78', 'CARRERA: ' . mb_strtoupper((string) ($data['plan']['programa'] ?? '')) . '                         (CONTINUACIÓN PLAN DEL PROCESO DOCENTE)');
        $sheet->setCellValue('B79', 'MODALIDAD: ' . mb_convert_case((string) ($data['plan']['modalidad'] ?? ''), MB_CASE_TITLE, 'UTF-8'));
    }

    private function clearTemplateData($sheet): void
    {
        foreach ([[14, 75], [85, 87], [88, 100], [101, 115]] as [$start, $end]) {
            for ($row = $start; $row <= $end; $row++) {
                foreach (range('B', 'L') as $column) {
                    $sheet->setCellValue($column . $row, null);
                }
            }
        }
    }

    private function fillBaseSection($sheet, Collection $rows, Collection $anios): array
    {
        $sheet->setCellValue('B14', 'CURRÍCULO BASE');
        $rowNumber = 15;
        $disciplinaNumber = 0;
        $asignaturaNumber = 0;

        foreach ($rows as $row) {
            if ($rowNumber > 74) {
                break;
            }

            if ($row['type'] === 'disciplina') {
                $disciplinaNumber++;
                $asignaturaNumber = 0;
                $this->writeAcademicRow($sheet, $rowNumber++, $disciplinaNumber, $row, $anios);
                continue;
            }

            $asignaturaNumber++;
            $this->writeAcademicRow($sheet, $rowNumber++, $disciplinaNumber . ',' . $asignaturaNumber, $row, $anios);
        }

        return $this->sectionTotals($rows, $anios);
    }

    private function fillSimpleSection($sheet, Collection $rows, Collection $anios, int $titleRow, int $startRow, int $endRow, string $title, string $prefix): array
    {
        $sheet->setCellValue('B' . $titleRow, $title);
        $rowNumber = $startRow;
        $counter = 0;

        foreach ($rows as $row) {
            if ($rowNumber > $endRow) {
                break;
            }

            if ($row['type'] === 'disciplina') {
                $this->writeAcademicRow($sheet, $rowNumber++, '', $row, $anios);
                continue;
            }

            $counter++;
            $codePrefix = $prefix;
            if ($prefix === 'O' && str_contains(mb_strtoupper($row['nombre']), 'ELECTIVA')) {
                $codePrefix = 'E';
            }
            $this->writeAcademicRow($sheet, $rowNumber++, $codePrefix . $counter, $row, $anios);
        }

        return $this->sectionTotals($rows, $anios);
    }

    private function writeAcademicRow($sheet, int $rowNumber, string|int $code, array $row, Collection $anios): void
    {
        $sheet->setCellValueExplicit(
            'B' . $rowNumber,
            (string) $code,
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
        $sheet->setCellValue('C' . $rowNumber, mb_strtoupper((string) $row['nombre']));
        $sheet->setCellValue('D' . $rowNumber, (int) $row['fondo_tiempo'] ?: null);
        $sheet->setCellValue('E' . $rowNumber, (int) $row['horas_clase'] ?: null);
        $sheet->setCellValue('F' . $rowNumber, (int) $row['horas_practica_laboral'] ?: null);
        $sheet->setCellValueExplicit(
            'G' . $rowNumber,
            $row['tiene_examen_final'] ? '1' : '',
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
        $sheet->setCellValueExplicit(
            'H' . $rowNumber,
            $row['tiene_trabajo_curso'] ? '1' : '',
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
        $this->writeYearValues($sheet, $rowNumber, $row['anios'] ?? [], $anios);
        $sheet->getStyle('B' . $rowNumber . ':L' . $rowNumber)
            ->getFont()
            ->setBold($row['type'] === 'disciplina');
    }

    private function writeYearValues($sheet, int $rowNumber, array $values, Collection $anios): void
    {
        foreach (['I', 'J', 'K', 'L'] as $index => $column) {
            $anio = $anios[$index]['identificador'] ?? null;
            $value = $anio ? (int) ($values[$anio] ?? 0) : 0;
            $sheet->setCellValue($column . $rowNumber, $value ?: null);
        }
    }

    private function writeTotals($sheet, int $startRow, string $label, array $totals, Collection $anios): void
    {
        $sheet->setCellValue('B' . $startRow, 'TOTAL DE HORAS DEL ' . $label . ' POR FORMAS Y AÑOS');
        $sheet->setCellValue('D' . $startRow, $totals['fondo_tiempo']);
        $sheet->setCellValue('E' . $startRow, $totals['horas_clase']);
        $sheet->setCellValue('F' . $startRow, $totals['horas_practica_laboral']);
        $this->writeYearValues($sheet, $startRow, $totals['anios'], $anios);

        $sheet->setCellValue('B' . ($startRow + 1), 'TOTAL DE EXÁMENES FINALES DEL ' . $label . ' POR AÑO');
        $sheet->setCellValue('G' . ($startRow + 1), $totals['examenes_finales']);
        $this->writeYearValues($sheet, $startRow + 1, $totals['examenes_por_anio'], $anios);

        $sheet->setCellValue('B' . ($startRow + 2), 'TOTAL DE TRABAJOS DE CURSO DEL ' . $label . ' POR AÑO');
        $sheet->setCellValue('H' . ($startRow + 2), $totals['trabajos_curso']);
        $this->writeYearValues($sheet, $startRow + 2, $totals['trabajos_por_anio'], $anios);
    }

    private function sectionTotals(Collection $rows, Collection $anios): array
    {
        $asignaturas = $rows->where('type', 'asignatura');

        return [
            'fondo_tiempo' => $asignaturas->sum('fondo_tiempo'),
            'horas_clase' => $asignaturas->sum('horas_clase'),
            'horas_practica_laboral' => $asignaturas->sum('horas_practica_laboral'),
            'examenes_finales' => $asignaturas->sum(fn ($row) => $row['tiene_examen_final'] ? 1 : 0),
            'trabajos_curso' => $asignaturas->sum(fn ($row) => $row['tiene_trabajo_curso'] ? 1 : 0),
            'anios' => $this->sumarAnios($asignaturas, $anios),
            'examenes_por_anio' => $this->sumarEvaluacionesPorAnio($asignaturas, $anios, 'tiene_examen_final'),
            'trabajos_por_anio' => $this->sumarEvaluacionesPorAnio($asignaturas, $anios, 'tiene_trabajo_curso'),
        ];
    }

    private function sumarEvaluacionesPorAnio(Collection $rows, Collection $anios, string $field): array
    {
        return $anios->mapWithKeys(function ($anio) use ($rows, $field) {
            $key = $anio['identificador'];

            return [
                $key => $rows->sum(fn ($row) => ($row[$field] ?? false) && (int) ($row['anios'][$key] ?? 0) > 0 ? 1 : 0),
            ];
        })->all();
    }

    private function mergeTotals(array $totalsList, Collection $anios): array
    {
        $emptyRows = collect();
        $merged = [
            'fondo_tiempo' => 0,
            'horas_clase' => 0,
            'horas_practica_laboral' => 0,
            'examenes_finales' => 0,
            'trabajos_curso' => 0,
            'anios' => $this->sumarAnios($emptyRows, $anios),
            'examenes_por_anio' => $this->sumarAnios($emptyRows, $anios),
            'trabajos_por_anio' => $this->sumarAnios($emptyRows, $anios),
        ];

        foreach ($totalsList as $totals) {
            foreach (['fondo_tiempo', 'horas_clase', 'horas_practica_laboral', 'examenes_finales', 'trabajos_curso'] as $key) {
                $merged[$key] += (int) ($totals[$key] ?? 0);
            }

            foreach (['anios', 'examenes_por_anio', 'trabajos_por_anio'] as $group) {
                foreach ($merged[$group] as $year => $value) {
                    $merged[$group][$year] = $value + (int) ($totals[$group][$year] ?? 0);
                }
            }
        }

        return $merged;
    }
}
