<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TD_PP extends Model
{
    protected $table = 'td_pp'; // 👈 importante

    protected $fillable = [
        'desarrollo_local',
        'sector_estrategico_id'
    ];

    public function sectorEstrategico()
    {
        return $this->belongsTo(SectorEstrategico::class);
    }
    public function estudiantes()
{
    return $this->hasMany(EstudianteTDPP::class);
}
}
