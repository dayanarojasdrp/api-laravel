<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facultad extends Model
{
    protected $table = 'facultad';
    protected $fillable = [
        'nombre',
        'abreviatura'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    use HasFactory;
    //Esto es lo que permite que no se elimine totalmente

}
