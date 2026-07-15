<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'recipe_template_id',
        'process',
        'step_number',
        'name',
        'code',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(RecipeTemplate::class, 'recipe_template_id');
    }
}
