<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;
    protected $table = 'asignatura';
    protected $fillable = [
        'nombre'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
