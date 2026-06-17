<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeTemplate extends Model
{
    protected $fillable = [
        'process',
        'title',
        'reference_source',
        'reference_code',
        'dosage_form',
        'source_note',
        'is_demo',
    ];

    protected $casts = [
        'is_demo' => 'boolean',
    ];

    public function steps()
    {
        return $this->hasMany(RecipeStep::class)->orderBy('step_number');
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
