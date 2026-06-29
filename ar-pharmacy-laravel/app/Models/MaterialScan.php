<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialScan extends Model
{
    protected $fillable = [
        'batch_id',
        'process',
        'step_number',
        'material_code',
        'material_name',
        'is_valid',
        'scanned_at',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'scanned_at' => 'datetime',
    ];
}
