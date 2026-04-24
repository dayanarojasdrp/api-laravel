<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AlumnoAyudante extends Model
{
    protected $table = 'alumno_ayudante';

    protected $fillable = [
        'id_estudiante',
        'nombre_tutor',
        'etapa',
        'fecha_inicio',
        'fecha_fin',
        'habilitado'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }
}
