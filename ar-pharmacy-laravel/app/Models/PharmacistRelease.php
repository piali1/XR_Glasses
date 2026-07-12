<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacistRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'reviewer_name',
        'reviewer_role',
        'decision',
        'risk_level',
        'material_summary',
        'checklist_summary',
        'issue_summary',
        'content_version_summary',
        'comment',
        'released_at',
    ];

    protected $casts = [
        'material_summary' => 'array',
        'checklist_summary' => 'array',
        'issue_summary' => 'array',
        'content_version_summary' => 'array',
        'released_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
