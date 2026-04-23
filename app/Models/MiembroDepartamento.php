<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MiembroDepartamento extends Model
{
    protected $table = 'miembro_departamento';

    protected $fillable = [
        'id_profesor',
        'id_departamento',
        'fecha_inicio',
        'fecha_fin',
        'habilitado'
    ];

    // 🔹 relaciones
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }
}
