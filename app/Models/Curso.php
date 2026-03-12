<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'curso';
    protected $fillable = [
        'curso'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    use HasFactory;
}
