<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('plan_notifications')) {
            return;
        }

        $this->replaceNotificationText([
            'Solicitud de modificacion recibida' => 'Solicitud de modificación recibida',
            'Modificacion aprobada por decano' => 'Modificación aprobada por decano',
            'Modificacion aprobada por vicerrector' => 'Modificación aprobada por vicerrector',
            'Modificacion rechazada por vicerrector' => 'Modificación rechazada por vicerrector',
            'Modificacion aprobada' => 'Modificación aprobada',
            'Modificacion cancelada' => 'Modificación cancelada',
            ' creo un nuevo plan de estudio' => ' creó un nuevo plan de estudio',
            ' envio una solicitud de modificacion' => ' envió una solicitud de modificación',
            ' aprobo el nuevo plan' => ' aprobó el nuevo plan',
            ' aprobo la modificacion' => ' aprobó la modificación',
            ' y lo envio para revision final' => ' y lo envió para revisión final',
            ' y la envio para revision final' => ' y la envió para revisión final',
            ' aprobo la modificacion del plan ' => ' aprobó la modificación del plan ',
            ' rechazo la modificacion del plan ' => ' rechazó la modificación del plan ',
            ' rechazo el nuevo plan ' => ' rechazó el nuevo plan ',
            ' la modificacion del plan ' => ' la modificación del plan ',
        ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('plan_notifications')) {
            return;
        }

        $this->replaceNotificationText([
            'Solicitud de modificación recibida' => 'Solicitud de modificacion recibida',
            'Modificación aprobada por decano' => 'Modificacion aprobada por decano',
            'Modificación aprobada por vicerrector' => 'Modificacion aprobada por vicerrector',
            'Modificación rechazada por vicerrector' => 'Modificacion rechazada por vicerrector',
            'Modificación aprobada' => 'Modificacion aprobada',
            'Modificación cancelada' => 'Modificacion cancelada',
            ' creó un nuevo plan de estudio' => ' creo un nuevo plan de estudio',
            ' envió una solicitud de modificación' => ' envio una solicitud de modificacion',
            ' aprobó el nuevo plan' => ' aprobo el nuevo plan',
            ' aprobó la modificación' => ' aprobo la modificacion',
            ' y lo envió para revisión final' => ' y lo envio para revision final',
            ' y la envió para revisión final' => ' y la envio para revision final',
            ' rechazó la modificación del plan ' => ' rechazo la modificacion del plan ',
            ' rechazó el nuevo plan ' => ' rechazo el nuevo plan ',
            ' la modificación del plan ' => ' la modificacion del plan ',
        ]);
    }

    private function replaceNotificationText(array $replacements): void
    {
        foreach ($replacements as $search => $replace) {
            DB::table('plan_notifications')->update([
                'title' => DB::raw("REPLACE(title, " . DB::getPdo()->quote($search) . ", " . DB::getPdo()->quote($replace) . ")"),
                'body' => DB::raw("REPLACE(body, " . DB::getPdo()->quote($search) . ", " . DB::getPdo()->quote($replace) . ")"),
            ]);
        }
    }
};
