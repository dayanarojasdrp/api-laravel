<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAppAccess extends Model
{
    public const APPLICATION_GESTION_ROLES = 'gestion_roles';
    public const APPLICATION_GESTION_PLAN_ESTUDIO = 'gestion_plan_estudio';

    public const ROLES_BY_APPLICATION = [
        self::APPLICATION_GESTION_ROLES => [
            'admin',
            'vicedecano_docente',
            'decano',
            'jefe_departamento',
        ],
        self::APPLICATION_GESTION_PLAN_ESTUDIO => [
            'admin',
            'jefe_departamento',
            'decano',
            'rector',
            'vicedecano_docente',
        ],
    ];

    protected $table = 'user_app_access';

    protected $fillable = [
        'username',
        'application_code',
        'role',
        'facultad_id',
        'departamento_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'facultad_id' => 'integer',
        'departamento_id' => 'integer',
    ];

    public static function applications(): array
    {
        return array_keys(self::ROLES_BY_APPLICATION);
    }

    public static function rolesForApplication(string $applicationCode): array
    {
        return self::ROLES_BY_APPLICATION[$applicationCode] ?? [];
    }
}
