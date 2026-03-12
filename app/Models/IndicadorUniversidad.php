<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IndicadorUniversidad extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'indicador_universidad';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'idCurso',
        'valor',
        'idIndicador',
        'idUniversidad',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
