<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    protected $table = 'universidad';
    protected $fillable = [
        'nombre',
        'abreviatura',
        'nivelDeAcreditacion',
        'direccion',
        'id_municipio',
        'id_provincia',
        'id_profesor'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    use HasFactory;

}
