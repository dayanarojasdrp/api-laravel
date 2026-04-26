<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;
    protected $table = 'profesor';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'apellidos',
        'idCatDocente',
        'idCatCientifica',
        'grado_titulo_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function catDocente()
{
    return $this->belongsTo(CatDocente::class, 'idCatDocente');
}

public function catCientifica()
{
    return $this->belongsTo(CatCientifica::class, 'idCatCientifica');
}
public function gradoTitulo()
{
    return $this->belongsTo(GradoTitulo::class, 'grado_titulo_id');
}

}
