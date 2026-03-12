<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanEstudio extends Model
{
    use HasFactory;
    protected $table = 'plan-estudio';
    protected $fillable = [
        'id_prog_form'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
