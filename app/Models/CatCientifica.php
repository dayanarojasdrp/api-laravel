<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatCientifica extends Model
{
    use HasFactory;
    protected $table = 'categoria_cientifica';
    protected $fillable = [
        'nombre'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
