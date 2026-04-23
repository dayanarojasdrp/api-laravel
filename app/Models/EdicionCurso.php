<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdicionCurso extends Model
{
    protected $table = 'edicion_curso';

    protected $fillable = [
        'edicion_id',
        'curso_id',
        'fecha_inicio',
        'fecha_fin'
    ];

    public function edicion()
    {
        return $this->belongsTo(Edicion::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
