<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'version',
        'valid_from',
        'valid_until',
        'area',
        'responsible_role',
        'approval_status',
        'process',
        'step_number',
        'display_context',
        'content',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'step_number' => 'integer',
    ];
}
