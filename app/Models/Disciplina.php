<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;
    protected $table = 'disciplina';
    protected $fillable = [
        'nombre',
        'fondo_tiempo'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
