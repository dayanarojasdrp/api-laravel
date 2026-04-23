<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Grupo extends Model
{
    public $timestamps = false; // 👈 no tienes created_at ni updated_at

    protected $fillable = []; // no hay campos editables aún
    public function anos()
{
    return $this->hasMany(AnoGrupo::class);
}
public function estudiantes()
{
    return $this->hasMany(EstudianteGrupo::class);
}

    protected $table = 'grupos'; // 👈 CLAVE

}
