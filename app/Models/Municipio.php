<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipio';
    protected $fillable = [
        'nombre',
        'id_provincia'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    use HasFactory;
    
}
