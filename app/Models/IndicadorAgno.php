<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndicadorAgno extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'indicador_agno';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'valor',
        'idCurso',
        'idIndicador',
        'idAnoAcademico'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
