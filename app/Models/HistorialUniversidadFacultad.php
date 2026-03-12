<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class HistorialUniversidadFacultad extends Model
{
    use HasUuids;
    protected $table = 'universidad_facultad';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_universidad',
        'id_facultad',
        'id_curso',
        'finished_at'
    ];
    protected $hidden = [
        'uuid',
        'updated_at'
    ];
    use HasFactory;
}
