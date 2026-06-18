<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Disciplina extends Model
{
    use HasFactory;
    protected $table = 'disciplina';
    protected $fillable = [
        'nombre',
        'fondo_tiempo'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function curriculos(): BelongsToMany
    {
        return $this->belongsToMany(
            Curriculo::class,
            'curriculo_disciplina',
            'id_disciplina',
            'id_curriculo'
        )->withPivot('id_prog_form');
    }

    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'disciplina_asignatura',
            'id_disciplina',
            'id_asignatura'
        );
    }
}
