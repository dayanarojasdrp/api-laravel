<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifestacion extends Model
{
    public $timestamps = false;

    protected $table = 'manifestaciones';

    protected $fillable = ['edicion_id'];

    public function edicion()
    {
        return $this->belongsTo(Edicion::class);
    }
    public function estudiantes()
{
    return $this->hasMany(EstudianteManifestacion::class);
}
}
