<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class ProfesorGuia extends Model
{
    protected $table = 'profesor_guia';

    protected $fillable = [
        'id_profesor',
        'id_grupo',
        'fecha_inicio',
        'fecha_fin',
        'habilitado'
    ];

    // 🔹 relaciones
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo'); // 👈 modelo Grupo debe apuntar a 'grupos'
    }
}
