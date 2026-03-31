<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificacion extends Model
{
    use HasFactory;
    protected $table = 'modificacion';
    protected $fillable = [
        'version_id',
        'nombre'
    ];
     protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function version()
    {
        return $this->belongsTo(Version::class, 'version_id');
    }
}
