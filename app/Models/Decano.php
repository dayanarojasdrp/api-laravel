<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decano extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'decano';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
  protected $fillable = [
    'id_facultad',
    'id_profesor',
    'fecha_inicio',
    'fecha_fin',
    'habilitado'
];
    protected $hidden = [
        'uuid',
        'updated_at'
    ];
    public function profesor()
{
    return $this->belongsTo(Profesor::class, 'id_profesor');
}

public function facultad()
{
    return $this->belongsTo(Facultad::class, 'id_facultad');
}

}
