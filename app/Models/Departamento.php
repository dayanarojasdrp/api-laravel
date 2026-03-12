<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    protected $table = 'departamento';
    protected $fillable = [
        'nombre',
        'abreviatura'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
