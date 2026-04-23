<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'curso';
    protected $fillable = [
        'curso',
        'version_id',
        'cohorte_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    use HasFactory;
    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id');
    }

    public function cohorte()
    {
        return $this->belongsTo(Cohorte::class, 'cohorte_id');
    }
public function edicionCursos()
{
    return $this->hasMany(EdicionCurso::class);
}

}
