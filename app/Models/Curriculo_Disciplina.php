<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Curriculo_Disciplina extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'curriculo_disciplina';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_curriculo',
        'id_curso',
        'id_disciplina'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
