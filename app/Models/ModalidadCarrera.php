<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalidadCarrera extends Model
{
    use HasFactory;
    protected $table = 'modalidad-carrera';
    protected $fillable = [
        'nombre'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
