<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanEstudioProgForm extends Model
{
    use HasFactory;
    protected $table='plan_de_estudio_programa_de_formacion';
    protected $fillable = [
        'programa_de_formacion_id',
        'plan_estudio_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
