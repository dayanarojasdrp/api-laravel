<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function documentFacultyId(): ?int
    {
        $value = request()->header('X-Facultad')
            ?? request('facultad_id')
            ?? request('id_facultad');

        return is_numeric($value) ? (int) $value : null;
    }

    protected function documentDepartmentId(): ?int
    {
        $value = request()->header('X-Departamento')
            ?? request('departamento_id')
            ?? request('id_departamento');

        return is_numeric($value) ? (int) $value : null;
    }

    protected function documentFacultyName(?int $facultadId): string
    {
        if (!$facultadId) {
            return 'Facultad';
        }

        return \App\Models\Facultad::find($facultadId)->nombre ?? 'Facultad';
    }

    protected function documentFacultyNameUpper(?int $facultadId): string
    {
        $nombre = $this->documentFacultyName($facultadId);

        return function_exists('mb_strtoupper')
            ? mb_strtoupper($nombre, 'UTF-8')
            : strtoupper($nombre);
    }

    protected function documentFileName(string $prefix, string $extension): string
    {
        $safePrefix = preg_replace('/[^A-Za-z0-9_-]+/', '_', trim($prefix));
        $safeUser = preg_replace('/[^A-Za-z0-9_-]+/', '_', request()->header('X-User', 'sin_usuario'));
        $timestamp = now()->format('Y-m-d_H-i-s_u');

        return "{$safePrefix}_{$timestamp}_{$safeUser}.{$extension}";
    }

    protected function logDocumentGenerated(string $documento, $periodo = null): void
    {
        $usuario = request()->header('X-User', 'desconocido');
        $detallePeriodo = $periodo ? " {$periodo}" : '';

        LogController::registrar(
            $usuario,
            'generar_documento',
            "{$usuario} generó {$documento}{$detallePeriodo}",
            $this->documentFacultyId()
        );
    }
}
