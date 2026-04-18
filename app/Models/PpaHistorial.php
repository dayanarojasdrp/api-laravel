<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpaHistorial extends Model
{
    protected $table = 'ppa_historial';

    protected $fillable = [
        'id_profesor',
        'id_a_academico',
        'id_curso',
        'accion',
        'fecha_accion'
    ];
}
