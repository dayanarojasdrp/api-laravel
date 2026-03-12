<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohorte extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'cohorte';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'curso_inicio',
        'curso_fin'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
