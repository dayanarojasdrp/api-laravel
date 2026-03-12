<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    use HasFactory;
    protected $table = 'indicador';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'tipoDeDato',
        'idTipoDeIndicador',
        'asociado'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
