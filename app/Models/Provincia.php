<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincia';
    protected $fillable = [
        'nombre'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    use HasFactory;
    
}
