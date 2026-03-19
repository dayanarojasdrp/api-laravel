<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgFormacion extends Model
{
    use HasFactory;
    protected $table = 'programa_de_formacion';
    protected $fillable = [
        'nombre',
        'abreviatura'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    
}
