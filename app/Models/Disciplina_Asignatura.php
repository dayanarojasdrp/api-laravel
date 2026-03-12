<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina_Asignatura extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'disciplina_asignatura';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_disciplina',
        'id_curso',
        'id_asignatura'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
