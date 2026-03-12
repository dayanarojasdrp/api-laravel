<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgFormModalidadCarrera extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'prog-form-modalidad-carrera';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_modalidad',
        'id_prog_form',
        'finished_at'
    ];
    protected $hidden = [
        'uuid',
        'updated_at'
    ];
}
