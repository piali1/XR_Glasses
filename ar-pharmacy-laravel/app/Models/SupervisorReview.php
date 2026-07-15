<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorReview extends Model
{
    protected $fillable = [
        'batch_id',
        'status',
        'reviewer_name',
        'comment',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
