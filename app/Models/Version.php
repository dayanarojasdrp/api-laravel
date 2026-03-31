<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;
    protected $table = 'version';
    protected $fillable = [
        'plan_estudio_id',
        'nombre'
    ];
     protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function planEstudio()
    {
        return $this->belongsTo(PlanEstudio::class, 'plan_estudio_id');
    }

    public function modificaciones()
    {
        return $this->hasMany(Modificacion::class, 'version_id');
    }

    public function cohortes()
    {
        return $this->hasMany(Cohorte::class, 'version_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'version_id');
    }

    
}
