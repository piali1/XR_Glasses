<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'process',
        'step_number',
        'name',
        'code',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];
}
