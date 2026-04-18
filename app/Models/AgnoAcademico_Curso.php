<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AgnoAcademico_Curso extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'a_academico_curso';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_a_academico',
        'id_curso',
        'id_cohorte'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
