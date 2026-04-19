<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPA extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'ppa';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_a_academico',
        'id_profesor',
        'id_curso',
        'finished_at'
    ];
    protected $hidden = [
        'uuid',
        'updated_at'
    ];
    public function profesor()
{
    return $this->belongsTo(Profesor::class, 'id_profesor');
}
public function añoAcademico()
{
    return $this->belongsTo(AñoAcademico::class, 'id_a_academico');
}
}
