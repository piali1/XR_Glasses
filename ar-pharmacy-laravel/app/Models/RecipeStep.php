<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeStep extends Model
{
    protected $fillable = [
        'recipe_template_id',
        'step_number',
        'title',
        'description',
        'warning',
        'ar_hint',
        'risk',
        'timer_seconds',
        'checklist_items',
    ];

    protected $casts = [
        'checklist_items' => 'array',
    ];

    public function template()
    {
        return $this->belongsTo(RecipeTemplate::class, 'recipe_template_id');
    }
}
