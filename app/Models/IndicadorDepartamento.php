<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorDepartamento extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'indicador_departamento';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'idCurso',
        'valor',
        'idIndicador',
        'idDepartamento',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
