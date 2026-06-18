<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curriculo extends Model
{
    use HasFactory;
    protected $table = 'curriculo';
    protected $fillable = [
        'nombre'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function disciplinas(): BelongsToMany
    {
        return $this->belongsToMany(
            Disciplina::class,
            'curriculo_disciplina',
            'id_curriculo',
            'id_disciplina'
        )->withPivot('id_prog_form');
    }

    public function planesEstudio(): BelongsToMany
    {
        return $this->belongsToMany(
            PlanEstudio::class,
            'plan-estudio_curriculo',
            'id_curriculo',
            'id_plan_estudio'
        );
    }
}
