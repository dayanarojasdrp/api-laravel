<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorAsignatura extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'indicador_asignatura';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'idCurso',
        'valor',
        'idIndicador',
        'idAsignatura',
        'idAnoAcademico'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
