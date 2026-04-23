<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    protected $fillable = [
    'nombre'
];
public function manifestaciones()
{
    return $this->hasMany(EstudianteManifestacion::class);
}
public function grupos()
{
    return $this->hasMany(EstudianteGrupo::class);
}
public function tdpps()
{
    return $this->hasMany(EstudianteTDPP::class);
}
}
