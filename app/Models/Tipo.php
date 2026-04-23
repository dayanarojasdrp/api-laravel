<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
public $timestamps = false; // 👈 CLAVE
    protected $guarded = [];
    public function ediciones()
    {
        return $this->hasMany(Edicion::class);
    }
}
