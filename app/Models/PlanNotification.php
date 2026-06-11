<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanNotification extends Model
{
    protected $fillable = [
        'recipient_username',
        'sender_username',
        'type',
        'title',
        'body',
        'plan_estudio_id',
        'read_at',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
}
