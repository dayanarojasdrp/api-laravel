<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnoAcademico extends Model
{
    use HasFactory;
    protected $table = 'a_academico';
    protected $fillable = [
        'identificador',
        'id_prog_form'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
