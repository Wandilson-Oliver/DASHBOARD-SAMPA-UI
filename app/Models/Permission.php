<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * Campos que podem ser atribuídos em massa
     */
    protected $fillable = [
        'name',
        'label',
        'module',
        'is_system',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Relacionamento: Permission → Roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Scopes úteis
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }
}
