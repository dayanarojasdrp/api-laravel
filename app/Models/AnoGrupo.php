<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class AnoGrupo extends Model
{
    protected $table = 'ano_grupo';

    protected $fillable = [
        'ano_academico_id',
        'grupo_id',
        'fecha'
    ];

    public function anoAcademico()
    {
        return $this->belongsTo(AnoAcademico::class, 'ano_academico_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
