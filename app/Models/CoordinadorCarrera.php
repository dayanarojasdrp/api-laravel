<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinadorCarrera extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'coordinador_de_carrera';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
   protected $fillable = [
    'id_prog_form',
    'id_profesor',
    'fecha_inicio',
    'fecha_fin',
    'habilitado'
];
    protected $hidden = [
        'uuid',
        'updated_at'
    ];
    public function profesor()
{
    return $this->belongsTo(Profesor::class, 'id_profesor');
}

public function programa()
{
    return $this->belongsTo(ProgFormacion::class, 'id_prog_form');
}
}
