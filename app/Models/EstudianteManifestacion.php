<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteManifestacion extends Model
{
    protected $table = 'estudiante_manifestacion';

    protected $fillable = [
        'estudiante_id',
        'manifestacion_id',
        'fecha'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function manifestacion()
    {
        return $this->belongsTo(Manifestacion::class);
    }
}
