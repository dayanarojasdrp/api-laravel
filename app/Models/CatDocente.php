<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatDocente extends Model
{
    use HasFactory;
    protected $table = 'categoria_docente';
    protected $fillable = [
        'nombre'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
