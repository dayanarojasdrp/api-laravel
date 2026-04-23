<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EstudianteTDPP extends Model
{
    protected $table = 'estudiante_td_pp';

    protected $fillable = [
        'estudiante_id',
        'td_pp_id',
        'fecha'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function tdpp()
    {
        return $this->belongsTo(TD_PP::class, 'td_pp_id');
    }
}
