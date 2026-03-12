<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;
    protected $table = 'profesor';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'apellidos',
        'idCatDocente',
        'idCatCientifica'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
