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
        'id_curso',
        'finished_at'
    ];
    protected $hidden = [
        'uuid',
        'updated_at'
    ];
}
