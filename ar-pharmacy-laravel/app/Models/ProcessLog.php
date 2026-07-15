<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessLog extends Model
{
    protected $fillable = [
        'batch_id',
        'step_number',
        'step_title',
        'timer_used',
        'materials_verified',
        'logged_at',
    ];

    protected $casts = [
        'timer_used' => 'boolean',
        'materials_verified' => 'boolean',
        'logged_at' => 'datetime',
    ];
}
