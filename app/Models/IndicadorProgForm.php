<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndicadorProgForm extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'indicador_progform';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'idCurso',
        'valor',
        'idIndicador',
        'idProgForm',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
