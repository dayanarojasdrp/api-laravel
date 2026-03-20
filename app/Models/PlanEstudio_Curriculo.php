<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class PlanEstudio_Curriculo extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'plan-estudio_curriculo';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id_plan_estudio',
        
        'id_curriculo'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
}
