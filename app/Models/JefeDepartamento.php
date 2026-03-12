<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeDepartamento extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'jefe_de_departamento';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_departamento',
        'id_profesor',
        'id_curso',
        'finished_at'
    ];
    protected $hidden = [
        'uuid',
        'updated_at'
    ];
}
