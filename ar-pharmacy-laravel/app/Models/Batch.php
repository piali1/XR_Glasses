<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'batch_id',
        'process',
        'operator_name',
        'workstation',
        'started_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(ProcessLog::class);
    }

    public function issues()
    {
        return $this->hasMany(ProcessIssue::class);
    }

    public function scans()
    {
        return $this->hasMany(MaterialScan::class);
    }
}
