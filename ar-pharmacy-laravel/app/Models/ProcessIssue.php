<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessIssue extends Model
{
    protected $fillable = [
        'batch_id',
        'step_number',
        'issue',
        'reported_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
    ];
}
