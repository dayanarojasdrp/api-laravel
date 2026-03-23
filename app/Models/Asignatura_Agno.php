<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Asignatura_Agno extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'asignatura_agno';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_asignatura',
        
        'id_a_academico'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
