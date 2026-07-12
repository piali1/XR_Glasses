<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class StaffUser extends Authenticatable
{
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'role_label',
        'department',
        'password',
        'permissions',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];
}
