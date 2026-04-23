<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edicion extends Model
{
    public $timestamps = false;

    protected $table = 'ediciones'; // 👈 ESTA ES LA CLAVE

    protected $fillable = ['tipo_id'];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }
    public function manifestaciones()
{
    return $this->hasMany(Manifestacion::class);
}
public function edicionCursos()
{
    return $this->hasMany(EdicionCurso::class);
}
}
